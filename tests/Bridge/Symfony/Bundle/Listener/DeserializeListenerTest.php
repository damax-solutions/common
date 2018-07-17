<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DeserializeListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeserializeListenerTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var ValidatorInterface|MockObject
     */
    private $validator;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp()
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new DeserializeListener($this->serializer, $this->validator));
    }

    /**
     * @test
     */
    public function it_skips_on_missing_annotation()
    {
        $event = $this->createEvent();

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        $this->assertNull($event->getRequest()->attributes->get('data'));
    }

    /**
     * @test
     */
    public function it_skips_on_unsupported_content_type()
    {
        $event = $this->createEvent(new Deserialize(['class' => stdClass::class]), 'text/plain');

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        $this->assertNull($event->getRequest()->attributes->get('data'));
    }

    /**
     * @test
     */
    public function it_fails_to_process_invalid_json_request()
    {
        $event = $this->createEvent(new Deserialize([
            'class' => stdClass::class,
            'groups' => ['foo', 'bar'],
        ]));

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('__content__', stdClass::class, 'json', ['groups' => ['foo', 'bar']])
            ->willThrowException(new UnsupportedException())
        ;
        $this->validator
            ->expects($this->never())
            ->method('validate')
        ;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Invalid json.');

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);
    }

    /**
     * @test
     */
    public function it_sets_processed_object_to_request_attribute()
    {
        $event = $this->createEvent(new Deserialize([
            'class' => stdClass::class,
            'param' => 'object',
        ]));

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with('__content__', stdClass::class, 'json', [])
            ->willReturn($object = new stdClass())
        ;
        $this->validator
            ->expects($this->never())
            ->method('validate')
        ;

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        $this->assertSame($object, $event->getRequest()->attributes->get('object'));
    }

    /**
     * @test
     */
    public function it_converts_invalid_json_request_to_problem_response()
    {
        $event = $this->createEvent(new Deserialize([
            'class' => stdClass::class,
            'validate' => true,
        ]));

        $this->serializer
            ->method('deserialize')
            ->willReturn($object = new stdClass())
        ;
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($object))
            ->willReturn($violations = new ConstraintViolationList([
                $this->createMock(ConstraintViolationInterface::class),
            ]))
        ;
        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($this->identicalTo($violations), 'json')
            ->willReturn('__errors__')
        ;

        $this->dispatcher->dispatch(KernelEvents::CONTROLLER, $event);

        /** @var JsonResponse $response */
        $response = call_user_func($event->getController());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('__errors__', $response->getContent());
        $this->assertArraySubset(['content-type' => ['application/problem+json']], $response->headers->all());
    }

    private function createEvent(Deserialize $annotation = null, string $contentType = 'application/json'): FilterControllerEvent
    {
        $kernel = $this->createMock(HttpKernelInterface::class);

        $request = new Request(
            [],
            [],
            $annotation ? ['_deserialize' => $annotation] : [],
            [],
            [],
            ['CONTENT_TYPE' => $contentType],
            '__content__'
        );

        return new FilterControllerEvent($kernel, function () {}, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}

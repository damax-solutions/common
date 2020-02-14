<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Validate;
use Damax\Common\Bridge\Symfony\Bundle\Listener\ValidateListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateListenerTest extends TestCase
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

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new ValidateListener($this->serializer, $this->validator));
    }

    /**
     * @test
     */
    public function it_sets_processed_object_to_request_attribute(): void
    {
        $event = $this->createEvent(new Validate(['groups' => [], 'param' => 'object']));

        $object = new stdClass();
        $event->getRequest()->attributes->set('object', $object);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->identicalTo($object))
            ->willReturn(new ConstraintViolationList([]))
        ;

        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);
    }

    /**
     * @test
     */
    public function it_converts_invalid_json_request_to_problem_response(): void
    {
        $event = $this->createEvent(new Validate([
            'groups' => [],
            'param' => 'data',
        ]));

        $object = new stdClass();
        $event->getRequest()->attributes->set('data', $object);

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

        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);

        /** @var JsonResponse $response */
        $response = call_user_func($event->getController());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('__errors__', $response->getContent());
        $this->assertTrue($response->headers->has('content-type'));
        $this->assertEquals('application/problem+json', $response->headers->get('content-type'));
    }

    private function createEvent(Validate $annotation = null, string $data = 'data', string $contentType = 'application/json'): ControllerEvent
    {
        $kernel = $this->createMock(HttpKernelInterface::class);

        $request = new Request(
            [],
            [],
            $annotation ? ['_validate' => $annotation, $data => []] : [],
            [],
            [],
            ['CONTENT_TYPE' => $contentType],
            '__content__'
        );

        return new ControllerEvent($kernel, function () {}, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}

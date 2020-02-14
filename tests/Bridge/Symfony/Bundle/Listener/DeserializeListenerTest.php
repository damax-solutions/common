<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Damax\Common\Bridge\Symfony\Bundle\Listener\DeserializeListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DeserializeListenerTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new DeserializeListener($this->serializer));
    }

    /**
     * @test
     */
    public function it_skips_on_missing_annotation(): void
    {
        $event = $this->createEvent();

        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);

        $this->assertNull($event->getRequest()->attributes->get('data'));
    }

    /**
     * @test
     */
    public function it_skips_on_unsupported_content_type(): void
    {
        $event = $this->createEvent(new Deserialize(['class' => stdClass::class]), 'text/plain');

        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);

        $this->assertNull($event->getRequest()->attributes->get('data'));
    }

    /**
     * @test
     */
    public function it_fails_to_process_invalid_json_request(): void
    {
        $event = $this->createEvent(
            new Deserialize(
                [
                    'class' => stdClass::class,
                    'ignore' => ['field1'],
                    'allowExtra' => false,
                    'constructorArgs' => [
                        stdClass::class => [
                            10, 'fof',
                        ],
                    ],
                    'refLimit' => 2,
                ]
            )
        );

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(
                '__content__',
                stdClass::class,
                'json',
                [
                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['field1'],
                    AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 2,
                    AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                    AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [
                        stdClass::class => [
                            10, 'fof',
                        ],
                    ],
                ]
            )
            ->willThrowException(new UnsupportedException())
        ;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Invalid json.');

        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);
    }

    /**
     * @test
     */
    public function it_sets_processed_object_to_request_attribute(): void
    {
        $event = $this->createEvent(
            new Deserialize(
                [
                    'class' => stdClass::class,
                    'param' => 'object',
                ]
            )
        );

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(
                '__content__',
                stdClass::class,
                'json',
                [
                    AbstractNormalizer::IGNORED_ATTRIBUTES => [],
                    AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 1,
                    AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
                    AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => [],
                ]
            )
            ->willReturn($object = new stdClass())
        ;

        $this->dispatcher->dispatch($event, KernelEvents::CONTROLLER);

        $this->assertSame($object, $event->getRequest()->attributes->get('object'));
    }

    private function createEvent(
        Deserialize $annotation = null,
        string $contentType = 'application/json'
    ): ControllerEvent {
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

        return new ControllerEvent(
            $kernel, function () {
            }, $request, HttpKernelInterface::MASTER_REQUEST
        );
    }
}

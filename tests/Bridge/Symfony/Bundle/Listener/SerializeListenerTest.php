<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\Common\Bridge\Symfony\Bundle\Listener\SerializeListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class SerializeListenerTest extends TestCase
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
        $this->dispatcher->addSubscriber(new SerializeListener($this->serializer));
    }

    /**
     * @test
     */
    public function it_skips_on_missing_annotation(): void
    {
        $event = $this->createEvent();

        $this->dispatcher->dispatch($event, KernelEvents::VIEW);

        $this->assertNull($event->getResponse());
    }

    /**
     * @test
     */
    public function it_skips_when_response_is_set(): void
    {
        $event = $this->createEvent(new Serialize(['value' => false]));
        $event->setResponse(new Response());

        $this->dispatcher->dispatch($event, KernelEvents::VIEW);

        $response = $event->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEmpty($response->getContent());
    }

    /**
     * @test
     */
    public function it_generates_json_response(): void
    {
        $event = $this->createEvent(new Serialize(['value' => false]), '__data__');

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('__data__', 'json', [AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => false])
            ->willReturn('{"body":"__data__"}')
        ;

        $this->dispatcher->dispatch($event, KernelEvents::VIEW);

        /** @var JsonResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('{"body":"__data__"}', $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(JSON_UNESCAPED_UNICODE, $response->getEncodingOptions());
    }

    /**
     * @test
     */
    public function it_generates_json_response_for_post_request(): void
    {
        $event = $this->createEvent(new Serialize(['value' => true]), '__data__');
        $event->getRequest()->setMethod('POST');

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with('__data__', 'json', [AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true])
            ->willReturn('{"body":"__data__"}')
        ;

        $this->dispatcher->dispatch($event, KernelEvents::VIEW);

        $this->assertEquals(201, $event->getResponse()->getStatusCode());
    }

    private function createEvent(Serialize $annotation = null, string $controllerResult = null): ViewEvent
    {
        $kernel = $this->createMock(HttpKernelInterface::class);

        $request = new Request([], [], ['_serialize' => $annotation]);

        return new ViewEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $controllerResult);
    }
}

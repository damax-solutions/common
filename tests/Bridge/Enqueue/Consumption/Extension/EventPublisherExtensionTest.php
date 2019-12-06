<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Enqueue\Consumption\Extension;

use Damax\Common\Bridge\Enqueue\Consumption\Extension\EventPublisherExtension;
use Damax\Common\Domain\EventPublisher\EventPublisher;
use Enqueue\Consumption\Context;
use Interop\Queue\PsrContext;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EventPublisherExtensionTest extends TestCase
{
    /**
     * @var EventPublisher|MockObject
     */
    private $publisher;

    /**
     * @var EventPublisherExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->publisher = $this->createMock(EventPublisher::class);
        $this->extension = new EventPublisherExtension($this->publisher);
    }

    /**
     * @test
     */
    public function it_publishes_events_on_post_received(): void
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $context = $this->createMock(PsrContext::class);

        $this->extension->onPostReceived(new Context($context));
    }

    /**
     * @test
     */
    public function it_publishes_events_when_interrupted(): void
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $context = $this->createMock(PsrContext::class);

        $this->extension->onInterrupted(new Context($context));
    }
}

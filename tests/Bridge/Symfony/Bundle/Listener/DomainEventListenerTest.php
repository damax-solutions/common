<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Domain\DomainEventPublisher;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainEventListenerTest extends TestCase
{
    /**
     * @var DomainEventPublisher|PHPUnit_Framework_MockObject_MockObject
     */
    private $publisher;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp()
    {
        $this->publisher = $this->createMock(DomainEventPublisher::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new DomainEventListener($this->publisher));
    }

    /**
     * @test
     */
    public function it_publishes_events_on_kernel_response()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $event = $this->createMock(FilterResponseEvent::class);

        $this->dispatcher->dispatch(KernelEvents::RESPONSE, $event);
    }

    /**
     * @test
     */
    public function it_discards_events_on_kernel_exception()
    {
        $this->publisher
            ->expects($this->once())
            ->method('discard')
        ;

        $event = $this->createMock(GetResponseForExceptionEvent::class);

        $this->dispatcher->dispatch(KernelEvents::EXCEPTION, $event);
    }

    /**
     * @test
     */
    public function it_publishes_events_on_console_termination()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $event = $this->createMock(ConsoleTerminateEvent::class);

        $this->dispatcher->dispatch(ConsoleEvents::TERMINATE, $event);
    }

    /**
     * @test
     */
    public function it_discards_events_on_console_error()
    {
        $this->publisher
            ->expects($this->once())
            ->method('discard')
        ;

        $event = new ConsoleErrorEvent(
            $this->createMock(InputInterface::class),
            $this->createMock(OutputInterface::class),
            new Exception()
        );

        $this->dispatcher->dispatch(ConsoleEvents::ERROR, $event);
    }
}

<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Bridge\Symfony\Bundle\Listener\DomainEventListener;
use Damax\Common\Domain\EventPublisher\EventPublisher;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainEventListenerTest extends TestCase
{
    /**
     * @var EventPublisher|MockObject
     */
    private $publisher;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    protected function setUp(): void
    {
        $this->publisher = $this->createMock(EventPublisher::class);
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber(new DomainEventListener($this->publisher));
    }

    /**
     * @test
     */
    public function it_publishes_events_on_kernel_response(): void
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $rc = new ReflectionClass(ResponseEvent::class);
        $event = $rc->newInstanceWithoutConstructor();

        $this->dispatcher->dispatch($event, KernelEvents::RESPONSE);
    }

    /**
     * @test
     */
    public function it_discards_events_on_kernel_exception(): void
    {
        $this->publisher
            ->expects($this->once())
            ->method('discard')
        ;

        $rc = new ReflectionClass(ExceptionEvent::class);
        $event = $rc->newInstanceWithoutConstructor();

        $this->dispatcher->dispatch($event, KernelEvents::EXCEPTION);
    }

    /**
     * @test
     */
    public function it_publishes_events_on_console_termination(): void
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $rc = new ReflectionClass(ConsoleTerminateEvent::class);
        $event = $rc->newInstanceWithoutConstructor();

        $this->dispatcher->dispatch($event, ConsoleEvents::TERMINATE);
    }

    /**
     * @test
     */
    public function it_discards_events_on_console_error(): void
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

        $this->dispatcher->dispatch($event, ConsoleEvents::ERROR);
    }
}

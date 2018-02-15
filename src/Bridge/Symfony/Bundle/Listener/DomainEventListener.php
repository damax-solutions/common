<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Domain\DomainEventPublisher;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainEventListener implements EventSubscriberInterface
{
    private $publisher;

    public function __construct(DomainEventPublisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', 1024],
            KernelEvents::EXCEPTION => ['onKernelException', 1024],
            ConsoleEvents::TERMINATE => ['onConsoleTerminate', 1024],
            ConsoleEvents::ERROR => ['onConsoleError', 1024],
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->publisher->publish();
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->publisher->discard();
    }

    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $this->publisher->publish();
    }

    public function onConsoleError(ConsoleErrorEvent $event)
    {
        $this->publisher->discard();
    }
}

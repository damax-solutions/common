<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Listener;

use Damax\Common\Domain\EventPublisher\EventPublisher;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DomainEventListener implements EventSubscriberInterface
{
    private $publisher;

    public function __construct(EventPublisher $publisher)
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

    public function onKernelResponse(): void
    {
        $this->publisher->publish();
    }

    public function onKernelException(): void
    {
        $this->publisher->discard();
    }

    public function onConsoleTerminate(): void
    {
        $this->publisher->publish();
    }

    public function onConsoleError(): void
    {
        $this->publisher->discard();
    }
}

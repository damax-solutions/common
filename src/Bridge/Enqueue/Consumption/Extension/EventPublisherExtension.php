<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Enqueue\Consumption\Extension;

use Damax\Common\Domain\EventPublisher\EventPublisher;
use Enqueue\Consumption\Context;
use Enqueue\Consumption\EmptyExtensionTrait;
use Enqueue\Consumption\ExtensionInterface;

final class EventPublisherExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    private $eventPublisher;

    public function __construct(EventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function onPostReceived(Context $context): void
    {
        $this->eventPublisher->publish();
    }

    public function onInterrupted(Context $context): void
    {
        $this->eventPublisher->publish();
    }
}

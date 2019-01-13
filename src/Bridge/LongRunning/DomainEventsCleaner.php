<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\LongRunning;

use Damax\Common\Domain\EventPublisher\EventPublisher;
use LongRunning\Core\Cleaner;

final class DomainEventsCleaner implements Cleaner
{
    /**
     * @var EventPublisher
     */
    private $publisher;

    public function __construct(EventPublisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function cleanUp(): void
    {
        $this->publisher->publish();
    }
}

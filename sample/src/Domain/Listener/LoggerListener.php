<?php

declare(strict_types=1);

namespace Sample\Domain\Listener;

use Psr\Log\LoggerInterface;
use Sample\Domain\Event\BookCreated;

final class LoggerListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onBookCreated(BookCreated $event)
    {
        $this->logger->debug('New book created.', [
            'id' => $event->bookId(),
            'author_id' => $event->authorId(),
            'title' => $event->title(),
            'occurred_on' => $event->occurredOn()->format('d/m/Y H:i:s'),
        ]);
    }
}

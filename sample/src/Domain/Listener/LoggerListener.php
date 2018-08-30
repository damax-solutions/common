<?php

declare(strict_types=1);

namespace App\Domain\Listener;

use App\Domain\Event\BookCreated;
use Psr\Log\LoggerInterface;

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

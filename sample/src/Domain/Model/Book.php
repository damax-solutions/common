<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Event\BookCreated;
use DateTimeImmutable;
use DateTimeInterface;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

class Book implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    private $id;
    private $authorId;
    private $title;
    private $createdAt;

    public function __construct(BookId $id, AuthorId $authorId, string $title)
    {
        $this->id = (string) $id;
        $this->authorId = (string) $authorId;
        $this->title = $title;
        $this->createdAt = new DateTimeImmutable();

        $this->record(new BookCreated($id, $authorId, $title, $this->createdAt));
    }

    public function id(): BookId
    {
        return BookId::fromString((string) $this->id);
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString((string) $this->authorId);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Model\AuthorId;
use App\Domain\Model\BookId;
use DateTimeInterface;
use JsonSerializable;

final class BookCreated implements JsonSerializable
{
    private $bookId;
    private $authorId;
    private $title;
    private $occurredOn;

    public function __construct(BookId $bookId, AuthorId $authorId, string $title, DateTimeInterface $occurredOn)
    {
        $this->bookId = (string) $bookId;
        $this->authorId = (string) $authorId;
        $this->title = $title;
        $this->occurredOn = $occurredOn;
    }

    public function bookId(): BookId
    {
        return BookId::fromString($this->bookId);
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}

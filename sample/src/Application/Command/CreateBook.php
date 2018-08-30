<?php

declare(strict_types=1);

namespace Sample\Application\Command;

use Sample\Domain\Model\AuthorId;

final class CreateBook
{
    private $authorId;
    private $title;

    public function __construct(string $authorId, string $title)
    {
        $this->authorId = $authorId;
        $this->title = $title;
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString($this->authorId);
    }

    public function title(): string
    {
        return $this->title;
    }
}

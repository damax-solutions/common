<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Author
{
    private $id;
    private $fullName;
    private $booksCount = 0;

    public function __construct(AuthorId $id, string $fullName)
    {
        $this->id = (string) $id;
        $this->fullName = $fullName;
    }

    public function id(): AuthorId
    {
        return AuthorId::fromString((string) $this->id);
    }

    public function fullName(): string
    {
        return $this->fullName;
    }

    public function booksCount(): int
    {
        return $this->booksCount;
    }

    public function incrementBooksCount(): void
    {
        ++$this->booksCount;
    }
}

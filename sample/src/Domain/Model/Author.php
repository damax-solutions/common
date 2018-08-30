<?php

declare(strict_types=1);

namespace App\Domain\Model;

class Author
{
    private $id;
    private $fullName;

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
}

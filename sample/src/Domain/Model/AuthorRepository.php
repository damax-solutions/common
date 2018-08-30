<?php

declare(strict_types=1);

namespace Sample\Domain\Model;

interface AuthorRepository
{
    public function byId(AuthorId $id): ?Author;

    public function add(Author $author): void;

    /**
     * @return Author[]
     */
    public function all(): array;
}

<?php

declare(strict_types=1);

namespace App\Domain\Model;

interface AuthorRepository
{
    public function byId(AuthorId $id): ?Author;

    public function add(Author $author): void;

    public function update(Author $author): void;

    /**
     * @return Author[]
     */
    public function all(): array;
}

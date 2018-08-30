<?php

declare(strict_types=1);

namespace App\Domain\Model;

use Pagerfanta\Pagerfanta;

interface BookRepository
{
    public function byId(BookId $id): ?Book;

    public function add(Book $book): void;

    public function paginate(AuthorId $authorId = null): Pagerfanta;
}

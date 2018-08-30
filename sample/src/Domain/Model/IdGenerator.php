<?php

declare(strict_types=1);

namespace App\Domain\Model;

interface IdGenerator
{
    public function bookId(): BookId;

    public function authorId(): AuthorId;
}

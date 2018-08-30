<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Dto\BookCreationDto;

final class CreateBook
{
    private $book;

    public function __construct(BookCreationDto $book)
    {
        $this->book = $book;
    }

    public function book(): BookCreationDto
    {
        return $this->book;
    }
}

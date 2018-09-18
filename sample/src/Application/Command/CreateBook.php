<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Dto\NewBookDto;

final class CreateBook
{
    private $book;

    public function __construct(NewBookDto $book)
    {
        $this->book = $book;
    }

    public function book(): NewBookDto
    {
        return $this->book;
    }
}

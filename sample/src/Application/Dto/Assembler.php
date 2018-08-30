<?php

declare(strict_types=1);

namespace Sample\Application\Dto;

use Sample\Domain\Model\Author;
use Sample\Domain\Model\Book;

final class Assembler
{
    public function toBookDto(Book $book): BookDto
    {
        $dto = new BookDto();

        $dto->id = (string) $book->id();
        $dto->authorId = (string) $book->authorId();
        $dto->title = $book->title();
        $dto->createdAt = $book->createdAt();

        return $dto;
    }

    public function toAuthorDto(Author $author): AuthorDto
    {
        $dto = new AuthorDto();

        $dto->id = (string) $author->id();
        $dto->fullName = $author->fullName();

        return $dto;
    }
}

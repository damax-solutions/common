<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\Assembler;
use App\Application\Dto\AuthorDto;
use App\Application\Exception\AuthorNotFound;
use App\Domain\Model\AuthorId;
use App\Domain\Model\AuthorRepository;

final class AuthorService
{
    private $authors;
    private $assembler;

    public function __construct(AuthorRepository $authors, Assembler $assembler)
    {
        $this->authors = $authors;
        $this->assembler = $assembler;
    }

    /**
     * @throws AuthorNotFound
     */
    public function fetch(string $id): AuthorDto
    {
        $authorId = AuthorId::fromString($id);

        if (null === $author = $this->authors->byId($authorId)) {
            throw AuthorNotFound::byId($authorId);
        }

        return $this->assembler->toAuthorDto($author);
    }

    /**
     * @return AuthorDto[]
     */
    public function fetchAll(): array
    {
        return array_map([$this->assembler, 'toAuthorDto'], $this->authors->all());
    }
}

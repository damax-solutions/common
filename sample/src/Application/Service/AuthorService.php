<?php

declare(strict_types=1);

namespace Sample\Application\Service;

use Sample\Application\Dto\Assembler;
use Sample\Application\Dto\AuthorDto;
use Sample\Application\Exception\AuthorNotFound;
use Sample\Domain\Model\AuthorId;
use Sample\Domain\Model\AuthorRepository;

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

<?php

declare(strict_types=1);

namespace Sample\Application\Service;

use Damax\Common\Pagerfanta\CallableDecoratorAdapter;
use Pagerfanta\Pagerfanta;
use Sample\Application\Command\CreateBook;
use Sample\Application\Dto\Assembler;
use Sample\Application\Dto\BookDto;
use Sample\Application\Exception\BookNotFound;
use Sample\Domain\Model\AuthorId;
use Sample\Domain\Model\Book;
use Sample\Domain\Model\BookId;
use Sample\Domain\Model\BookRepository;
use Sample\Domain\Model\IdGenerator;

final class BookService
{
    private $books;
    private $idGenerator;
    private $assembler;

    public function __construct(BookRepository $books, IdGenerator $idGenerator, Assembler $assembler)
    {
        $this->books = $books;
        $this->idGenerator = $idGenerator;
        $this->assembler = $assembler;
    }

    public function create(CreateBook $command): BookDto
    {
        $book = new Book($this->idGenerator->bookId(), $command->authorId(), $command->title());

        $this->books->add($book);

        return $this->assembler->toBookDto($book);
    }

    /**
     * @throws BookNotFound
     */
    public function fetch(string $id): BookDto
    {
        $bookId = BookId::fromString($id);

        if (null === $book = $this->books->byId($bookId)) {
            throw BookNotFound::byId($bookId);
        }

        return $this->assembler->toBookDto($book);
    }

    public function fetchRange(string $authorId = null): Pagerfanta
    {
        $adapter = $this->books
            ->paginate($authorId ? AuthorId::fromString($authorId) : null)
            ->getAdapter()
        ;

        return new Pagerfanta(new CallableDecoratorAdapter($adapter, [$this->assembler, 'toBookDto']));
    }
}

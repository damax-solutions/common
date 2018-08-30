<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Command\CreateBook;
use App\Application\Dto\Assembler;
use App\Application\Dto\BookDto;
use App\Application\Exception\BookNotFound;
use App\Domain\Model\AuthorId;
use App\Domain\Model\Book;
use App\Domain\Model\BookId;
use App\Domain\Model\BookRepository;
use App\Domain\Model\IdGenerator;
use Damax\Common\Pagerfanta\CallableDecoratorAdapter;
use Pagerfanta\Pagerfanta;

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
        $info = $command->book();
        $book = new Book($this->idGenerator->bookId(), AuthorId::fromString($info['author_id']), $info['title']);

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

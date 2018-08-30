<?php

declare(strict_types=1);

namespace App\Doctrine\Orm;

use App\Domain\Model\AuthorId;
use App\Domain\Model\Book;
use App\Domain\Model\BookId;
use App\Domain\Model\BookRepository as BookRepositoryInterface;
use Damax\Common\Doctrine\Orm\OrmRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

final class BookRepository implements BookRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $className = Book::class)
    {
        $this->em = $em;
        $this->className = $className;
    }

    public function byId(BookId $id): ?Book
    {
        /** @var Book $book */
        $book = $this->em->find($this->className, $id);

        return $book;
    }

    public function add(Book $book): void
    {
        $this->em->persist($book);
        $this->em->flush($book);
    }

    public function paginate(AuthorId $authorId = null): Pagerfanta
    {
        $qb = $this->createQueryBuilder('b')->orderBy('b.createdAt', 'DESC');

        if ($authorId) {
            $qb->where('b.authorId = :author_id')->setParameter('author_id', $authorId);
        }

        return new Pagerfanta(new DoctrineORMAdapter($qb, true, false));
    }
}

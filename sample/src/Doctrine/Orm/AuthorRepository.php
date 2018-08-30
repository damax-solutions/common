<?php

declare(strict_types=1);

namespace Sample\Doctrine\Orm;

use Damax\Common\Doctrine\Orm\OrmRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sample\Domain\Model\Author;
use Sample\Domain\Model\AuthorId;
use Sample\Domain\Model\AuthorRepository as AuthorRepositoryInterface;

final class AuthorRepository implements AuthorRepositoryInterface
{
    use OrmRepositoryTrait;

    public function __construct(EntityManagerInterface $em, string $className = Author::class)
    {
        $this->em = $em;
        $this->className = $className;
    }

    public function byId(AuthorId $id): ?Author
    {
        /** @var Author $author */
        $author = $this->em->find($this->className, (string) $id);

        return $author;
    }

    public function add(Author $author): void
    {
        $this->em->persist($author);
        $this->em->flush($author);
    }

    public function all(): array
    {
        return $this->createQueryBuilder('a')->getQuery()->getResult();
    }
}

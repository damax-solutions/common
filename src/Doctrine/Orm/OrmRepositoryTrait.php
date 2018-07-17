<?php

declare(strict_types=1);

namespace Damax\Common\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @codeCoverageIgnore
 */
trait OrmRepositoryTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $className;

    private function createQueryBuilder(string $alias): QueryBuilder
    {
        return $this->em
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->className, $alias)
        ;
    }
}

<?php

declare(strict_types=1);

namespace Damax\Common\Doctrine\Orm;

use Closure;
use Damax\Common\Domain\Transaction\TransactionManager as TransactionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionManager implements TransactionManagerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function begin(): void
    {
        $this->em->beginTransaction();
    }

    public function commit(): void
    {
        $this->em->commit();
    }

    public function rollback(): void
    {
        $this->em->rollback();
    }

    public function run(Closure $fn): void
    {
        $this->em->transactional($fn);
    }
}

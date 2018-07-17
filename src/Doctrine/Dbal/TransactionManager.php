<?php

declare(strict_types=1);

namespace Damax\Common\Doctrine\Dbal;

use Closure;
use Damax\Common\Domain\Transaction\TransactionManager as TransactionManagerInterface;
use Doctrine\DBAL\Connection;

final class TransactionManager implements TransactionManagerInterface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function begin(): void
    {
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollback(): void
    {
        $this->db->rollBack();
    }

    public function run(Closure $fn): void
    {
        $this->db->transactional($fn);
    }
}

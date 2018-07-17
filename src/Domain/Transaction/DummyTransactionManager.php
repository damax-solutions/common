<?php

declare(strict_types=1);

namespace Damax\Common\Domain\Transaction;

use Closure;

final class DummyTransactionManager implements TransactionManager
{
    public function begin(): void
    {
    }

    public function commit(): void
    {
    }

    public function rollback(): void
    {
    }

    public function run(Closure $fn): void
    {
        call_user_func($fn);
    }
}

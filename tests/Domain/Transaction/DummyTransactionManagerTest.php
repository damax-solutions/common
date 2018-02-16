<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Domain\Transaction;

use Damax\Common\Domain\Transaction\DummyTransactionManager;
use PHPUnit\Framework\TestCase;

class DummyTransactionManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_runs_transaction()
    {
        $ran = false;

        (new DummyTransactionManager())->run(function () use (&$ran) {
            $ran = true;
        });

        $this->assertTrue($ran);
    }
}

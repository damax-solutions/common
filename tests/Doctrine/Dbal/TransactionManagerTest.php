<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Doctrine\Dbal;

use Damax\Common\Doctrine\Dbal\TransactionManager;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class TransactionManagerTest extends TestCase
{
    /**
     * @var Connection|PHPUnit_Framework_MockObject_MockObject
     */
    private $db;

    /**
     * @var TransactionManager
     */
    private $manager;

    protected function setUp()
    {
        $this->db = $this->createMock(Connection::class);
        $this->manager = new TransactionManager($this->db);
    }

    /**
     * @test
     */
    public function it_begins_transaction()
    {
        $this->db
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $this->manager->begin();
    }

    /**
     * @test
     */
    public function it_commits_transaction()
    {
        $this->db
            ->expects($this->once())
            ->method('commit')
        ;

        $this->manager->commit();
    }

    /**
     * @test
     */
    public function it_rollbacks_transaction()
    {
        $this->db
            ->expects($this->once())
            ->method('rollback')
        ;

        $this->manager->rollback();
    }

    /**
     * @test
     */
    public function it_runs_transaction()
    {
        $fn = function () {
        };

        $this->db
            ->expects($this->once())
            ->method('transactional')
            ->with($this->identicalTo($fn))
        ;

        $this->manager->run($fn);
    }
}

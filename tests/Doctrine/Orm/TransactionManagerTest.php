<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Doctrine\Orm;

use Damax\Common\Doctrine\Orm\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransactionManagerTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $em;

    /**
     * @var TransactionManager
     */
    private $manager;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->manager = new TransactionManager($this->em);
    }

    /**
     * @test
     */
    public function it_begins_transaction(): void
    {
        $this->em
            ->expects($this->once())
            ->method('beginTransaction')
        ;

        $this->manager->begin();
    }

    /**
     * @test
     */
    public function it_commits_transaction(): void
    {
        $this->em
            ->expects($this->once())
            ->method('commit')
        ;

        $this->manager->commit();
    }

    /**
     * @test
     */
    public function it_rollbacks_transaction(): void
    {
        $this->em
            ->expects($this->once())
            ->method('rollback')
        ;

        $this->manager->rollback();
    }

    /**
     * @test
     */
    public function it_runs_transaction(): void
    {
        $fn = function () {
        };

        $this->em
            ->expects($this->once())
            ->method('transactional')
            ->with($this->identicalTo($fn))
        ;

        $this->manager->run($fn);
    }
}

<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\LongRunning;

use Damax\Common\Bridge\LongRunning\DomainEventsCleaner;
use Damax\Common\Domain\EventPublisher\EventPublisher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DomainEventsCleanerTest extends TestCase
{
    /**
     * @var EventPublisher|MockObject
     */
    private $publisher;

    /**
     * @var DomainEventsCleaner
     */
    private $cleaner;

    protected function setUp()
    {
        $this->publisher = $this->createMock(EventPublisher::class);
        $this->cleaner = new DomainEventsCleaner($this->publisher);
    }

    /**
     * @test
     */
    public function it_publishes_domain_events()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
        ;

        $this->cleaner->cleanUp();
    }
}

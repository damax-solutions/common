<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Domain;

use Damax\Common\Domain\DomainEventPublisher;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use stdClass;

class DomainEventPublisherTest extends TestCase
{
    /**
     * @var ContainsRecordedMessages|PHPUnit_Framework_MockObject_MockObject
     */
    private $recorder;

    /**
     * @var MessageBus|PHPUnit_Framework_MockObject_MockObject
     */
    private $messageBus;

    /**
     * @var DomainEventPublisher
     */
    private $publisher;

    protected function setUp()
    {
        $this->recorder = $this->createMock(ContainsRecordedMessages::class);
        $this->messageBus = $this->createMock(MessageBus::class);
        $this->publisher = new DomainEventPublisher($this->recorder, $this->messageBus);
    }

    /**
     * @test
     */
    public function it_discards_messages()
    {
        $this->recorder
            ->expects($this->once())
            ->method('eraseMessages')
        ;
        $this->recorder
            ->expects($this->never())
            ->method('recordedMessages')
        ;

        $this->publisher->discard();
    }

    /**
     * @test
     */
    public function it_publishes_no_messages()
    {
        $this->recorder
            ->expects($this->once())
            ->method('recordedMessages')
            ->willReturn([])
        ;
        $this->recorder
            ->expects($this->never())
            ->method('eraseMessages')
        ;

        $this->publisher->publish();
    }

    /**
     * @test
     */
    public function it_publishes_messages()
    {
        $msg1 = new stdClass();
        $msg2 = new stdClass();

        $this->recorder
            ->expects($this->once())
            ->method('recordedMessages')
            ->willReturn([$msg1, $msg2])
        ;
        $this->recorder
            ->expects($this->once())
            ->method('eraseMessages')
        ;
        $this->messageBus
            ->expects($this->exactly(2))
            ->method('handle')
            ->withConsecutive(
                [$this->identicalTo($msg1)],
                [$this->identicalTo($msg2)]
            )
        ;

        $this->publisher->publish();
    }
}

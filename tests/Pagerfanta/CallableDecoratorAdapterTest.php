<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Pagerfanta;

use Damax\Common\Pagerfanta\CallableDecoratorAdapter;
use Pagerfanta\Adapter\AdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CallableDecoratorAdapterTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $decorated;

    protected function setUp()
    {
        $this->decorated = $this->createMock(AdapterInterface::class);
    }

    /**
     * @test
     */
    public function it_retrieves_total_count()
    {
        $this->decorated
            ->expects($this->once())
            ->method('getNbResults')
            ->willReturn(10)
        ;

        $adapter = new CallableDecoratorAdapter($this->decorated, function () {});

        $this->assertEquals(10, $adapter->getNbResults());
    }

    /**
     * @test
     */
    public function it_applies_callback_on_items()
    {
        $this->decorated
            ->expects($this->once())
            ->method('getSlice')
            ->with(10, 20)
            ->willReturn(['foo', 'bar'])
        ;

        $adapter = new CallableDecoratorAdapter($this->decorated, function (string $item): string {
            return '___' . $item . '___';
        });

        $this->assertEquals(['___foo___', '___bar___'], $adapter->getSlice(10, 20));
    }
}

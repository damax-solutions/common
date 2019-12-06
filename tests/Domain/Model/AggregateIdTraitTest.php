<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Domain\Model;

use PHPUnit\Framework\TestCase;

class AggregateIdTraitTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_id(): void
    {
        $this->assertEquals('abc', (string) AggregateRootId::fromString('abc'));
    }
}

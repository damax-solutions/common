<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Annotation;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_annotation_with_default_properties(): void
    {
        $annotation = new Serialize(['foo', 'bar']);

        $this->assertEquals(['foo', 'bar'], $annotation->groups());
        $this->assertEquals('serialize', $annotation->getAliasName());
        $this->assertFalse($annotation->allowArray());
    }

    /**
     * @test
     */
    public function it_creates_annotation(): void
    {
        $annotation = new Serialize(['value' => ['foo', 'bar']]);

        $this->assertEquals(['foo', 'bar'], $annotation->groups());
    }
}

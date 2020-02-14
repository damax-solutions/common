<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Annotation;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeserializeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_annotation_with_default_properties(): void
    {
        $annotation = new Deserialize(['value' => stdClass::class]);

        $this->assertEquals(stdClass::class, $annotation->className());
        $this->assertEquals('data', $annotation->param());
        $this->assertEquals('deserialize', $annotation->getAliasName());
        $this->assertFalse($annotation->allowArray());
    }

    /**
     * @test
     */
    public function it_creates_annotation(): void
    {
        $annotation = new Deserialize([
            'class' => stdClass::class,
            'param' => 'command',
        ]);

        $this->assertEquals(stdClass::class, $annotation->className());
        $this->assertEquals('command', $annotation->param());
    }
}

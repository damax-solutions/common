<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Application;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class AsArrayTraitTest extends TestCase
{
    /**
     * @var ObjectAsArray
     */
    private $object;

    protected function setUp()
    {
        $this->object = new ObjectAsArray();
        $this->object->fooField = 'Foo value';
        $this->object->barField = 123;
        $this->object->bazField = new ObjectAsArray();
        $this->object->bazField->fooField = 'Internal foo value';
        $this->object->bazField->barField = 456;
    }

    /**
     * @test
     */
    public function it_accesses_properties()
    {
        $this->assertTrue(isset($this->object['foo_field']));
        $this->assertTrue(isset($this->object['bar_field']));
        $this->assertTrue(isset($this->object['baz_field']));
        $this->assertFalse(isset($this->object['qux_field']));

        $this->assertEquals('Foo value', $this->object['foo_field']);
        $this->assertEquals(123, $this->object['bar_field']);
        $this->assertEquals('Internal foo value', $this->object['baz_field']['foo_field']);
        $this->assertEquals(456, $this->object['baz_field']['bar_field']);
        $this->assertNull($this->object['baz_field']['baz_field']);
    }

    /**
     * @test
     */
    public function it_forbids_property_mutation()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method "Damax\Common\Application\AsArrayTrait::offsetSet" not implemented.');

        $this->object['foo_field'] = 'New value';
    }

    /**
     * @test
     */
    public function it_forbids_property_deletion()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method "Damax\Common\Application\AsArrayTrait::offsetUnset" not implemented.');

        unset($this->object['foo_field']);
    }
}

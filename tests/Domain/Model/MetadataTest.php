<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Domain\Model;

use Damax\Common\Domain\Model\Metadata;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class MetadataTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_metadata(): void
    {
        $this->assertEquals([], Metadata::create()->all());
    }

    /**
     * @test
     */
    public function it_creates_from_array(): Metadata
    {
        $metadata = Metadata::fromArray(['foo' => 'bar', 'baz' => 'qux']);

        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $metadata->all());

        return $metadata;
    }

    /**
     * @test
     */
    public function it_creates_from_key_value(): void
    {
        $metadata = Metadata::kv('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $metadata->all());
    }

    /**
     * @test
     *
     * @depends it_creates_from_array
     */
    public function it_checks_key_existence(Metadata $metadata): void
    {
        $this->assertTrue($metadata->has('foo'));
        $this->assertTrue($metadata->has('baz'));
        $this->assertFalse($metadata->has('xyz'));
    }

    /**
     * @test
     *
     * @depends it_creates_from_array
     */
    public function it_retrieves_value_by_key(Metadata $metadata): void
    {
        $this->assertEquals('bar', $metadata->get('foo'));
        $this->assertEquals('qux', $metadata->get('baz'));
    }

    /**
     * @test
     *
     * @depends it_creates_from_array
     */
    public function it_retrieves_all_values(Metadata $metadata): void
    {
        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $metadata->all());
    }

    /**
     * @test
     *
     * @depends it_creates_from_array
     */
    public function it_retrieves_missing_key(Metadata $metadata): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Value by key "abc" not found.');

        $metadata->get('abc');
    }

    /**
     * @test
     *
     * @depends it_creates_from_array
     */
    public function it_serializes_to_json(Metadata $metadata): void
    {
        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $metadata->jsonSerialize());
    }

    /**
     * @test
     */
    public function it_merges_metadata(): void
    {
        $one = Metadata::kv('foo', 'bar');
        $two = Metadata::kv('baz', 'qux');

        $this->assertEquals(['foo' => 'bar', 'baz' => 'qux'], $one->merge($two)->all());
    }
}

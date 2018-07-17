<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\RamseyUuid;

use Damax\Common\Bridge\RamseyUuid\UuidNormalizer;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use stdClass;

class UuidNormalizerTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_denormalization_is_supported()
    {
        $normalizer = new UuidNormalizer();

        $this->assertFalse($normalizer->supportsDenormalization('__data__', stdClass::class));
        $this->assertTrue($normalizer->supportsDenormalization('__data__', UuidInterface::class));
    }

    /**
     * @test
     */
    public function it_denormalizes_data()
    {
        $uuid = (new UuidNormalizer())->denormalize('dbb93230-8472-46e0-8ba0-aa480782ff66', UuidInterface::class);

        $this->assertInstanceOf(UuidInterface::class, $uuid);
        $this->assertEquals('dbb93230-8472-46e0-8ba0-aa480782ff66', (string) $uuid);
    }
}

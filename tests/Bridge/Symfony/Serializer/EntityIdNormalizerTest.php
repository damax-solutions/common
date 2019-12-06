<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Serializer;

use Damax\Common\Bridge\Symfony\Serializer\EntityIdNormalizer;
use Damax\Common\Tests\Domain\Model\AggregateRootId;
use PHPUnit\Framework\TestCase;
use stdClass;

class EntityIdNormalizerTest extends TestCase
{
    /**
     * @test
     */
    public function it_supports_denormalization(): void
    {
        $normalizer = new EntityIdNormalizer(AggregateRootId::class);
        $this->assertTrue($normalizer->supportsDenormalization('', AggregateRootId::class));

        $normalizer = new EntityIdNormalizer(AggregateRootId::class);
        $this->assertFalse($normalizer->supportsDenormalization('', stdClass::class));
    }

    /**
     * @test
     */
    public function it_denormalizes_data(): void
    {
        $normalizer = new EntityIdNormalizer(AggregateRootId::class);
        $entityId = $normalizer->denormalize('ABC', AggregateRootId::class);

        $this->assertInstanceOf(AggregateRootId::class, $entityId);
        $this->assertEquals('ABC', (string) $entityId);
    }
}

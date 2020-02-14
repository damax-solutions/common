<?php

declare(strict_types=1);

namespace Damax\Common\Tests\Bridge\Symfony\Bundle\Annotation;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class SerializeTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_annotation_with_default_properties(): void
    {
        $annotation = new Serialize(['value' => false]);

        $this->assertEquals(
            [
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => false,
            ],
            $annotation->context()
        );
        $this->assertEquals('serialize', $annotation->getAliasName());
        $this->assertFalse($annotation->allowArray());
    }

    /**
     * @test
     */
    public function it_creates_annotation_with_value(): void
    {
        $annotation = new Serialize(['value' => true]);
        $this->assertEquals(
            [
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            ],
            $annotation->context()
        );
    }

    /**
     * @test
     */
    public function it_creates_annotation_with_named_param(): void
    {
        $annotation = new Serialize(['deepPopulate' => true]);
        $this->assertEquals(
            [
                AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            ],
            $annotation->context()
        );
    }
}

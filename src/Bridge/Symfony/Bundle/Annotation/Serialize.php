<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * @Annotation
 */
final class Serialize implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $deepPopulate;

    public function __construct(array $data)
    {
        $this->deepPopulate = (bool) ($data['deepPopulate'] ?? $data['value']);
    }

    public function context(): array
    {
        return [
            AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => $this->deepPopulate,
        ];
    }

    public function getAliasName(): string
    {
        return 'serialize';
    }

    public function allowArray(): bool
    {
        return false;
    }
}

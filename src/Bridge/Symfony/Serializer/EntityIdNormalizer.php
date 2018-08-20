<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class EntityIdNormalizer implements DenormalizerInterface
{
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return call_user_func([$this->className, 'fromString'], $data);
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_a($type, $this->className, true);
    }
}

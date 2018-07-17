<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\RamseyUuid;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Copied from https://github.com/api-platform/core/blob/master/src/Bridge/RamseyUuid/Identifier/Normalizer/UuidNormalizer.php.
 */
final class UuidNormalizer implements DenormalizerInterface
{
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return Uuid::fromString($data);
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_a($type, UuidInterface::class, true);
    }
}

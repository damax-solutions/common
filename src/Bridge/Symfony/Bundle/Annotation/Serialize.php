<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
final class Serialize implements ConfigurationInterface
{
    private $groups;

    public function __construct(array $groups)
    {
        $this->groups = $groups['value'] ?? $groups;
    }

    public function groups(): array
    {
        return $this->groups;
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

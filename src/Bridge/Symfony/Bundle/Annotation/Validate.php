<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
final class Validate implements ConfigurationInterface
{
    /**
     * @var string[]
     */
    private $groups;

    /**
     * @var string
     */
    private $param;

    public function __construct(array $data)
    {
        $this->groups = (array) ($data['groups'] ?? $data['value']);
        $this->param = $data['param'] ?? 'data';
    }

    public function groups(): array
    {
        return $this->groups;
    }

    public function param(): string
    {
        return $this->param;
    }

    public function getAliasName(): string
    {
        return 'validate';
    }

    public function allowArray(): bool
    {
        return false;
    }
}

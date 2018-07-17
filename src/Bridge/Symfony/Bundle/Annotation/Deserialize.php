<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
final class Deserialize implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var bool
     */
    private $validate;

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
        $this->class = $data['class'] ?? $data['value'];
        $this->validate = $data['validate'] ?? false;
        $this->groups = $data['groups'] ?? [];
        $this->param = $data['param'] ?? 'data';
    }

    public function className(): string
    {
        return $this->class;
    }

    public function validate(): bool
    {
        return $this->validate;
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
        return 'deserialize';
    }

    public function allowArray(): bool
    {
        return false;
    }
}

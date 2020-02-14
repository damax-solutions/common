<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
     * @var array
     */
    private $ignore;

    /**
     * @var int
     */
    private $refLimit;

    /**
     * @var bool
     */
    private $allowExtra;

    /**
     * @var array
     */
    private $constructorArgs;

    /**
     * @var string
     */
    private $param;

    public function __construct(array $data)
    {
        $this->class = $data['class'] ?? $data['value'];

        $this->ignore = (array) ($data['ignore'] ?? []);
        $this->refLimit = (int) ($data['refLimit'] ?? 1);
        $this->allowExtra = (bool) ($data['allowExtra'] ?? true);
        $this->constructorArgs = (array) ($data['constructorArgs'] ?? []);

        $this->param = $data['param'] ?? 'data';
    }

    public function className(): string
    {
        return $this->class;
    }

    public function context(): array
    {
        return [
            AbstractNormalizer::IGNORED_ATTRIBUTES => $this->ignore,
            AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => $this->refLimit,
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => $this->allowExtra,
            AbstractNormalizer::DEFAULT_CONSTRUCTOR_ARGUMENTS => $this->constructorArgs,
        ];
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

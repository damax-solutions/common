<?php

declare(strict_types=1);

namespace Damax\Common\Bridge\Symfony\Bundle\Annotation;

use RuntimeException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
class Command implements ConfigurationInterface
{
    private $data;

    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data['class'] = $data['value'];
        }

        if (empty($data['class'])) {
            throw new RuntimeException(sprintf('Key "class" is not defined for annotation "@%s"', __CLASS__));
        }

        $this->data = $data;
    }

    public function className(): string
    {
        return $this->data['class'];
    }

    public function validate(): bool
    {
        return $this->data['validate'] ?? false;
    }

    public function getAliasName(): string
    {
        return 'command';
    }

    public function allowArray(): bool
    {
        return false;
    }
}

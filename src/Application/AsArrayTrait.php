<?php

declare(strict_types=1);

namespace Damax\Common\Application;

use BadMethodCallException;
use Doctrine\Common\Inflector\Inflector;

trait AsArrayTrait
{
    public function offsetExists($offset): bool
    {
        $prop = Inflector::camelize($offset);

        return isset($this->$prop);
    }

    public function offsetGet($offset)
    {
        $prop = Inflector::camelize($offset);

        return $this->$prop;
    }

    /**
     * @throws BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException(sprintf('Method "%s" not implemented.', __METHOD__));
    }

    /**
     * @throws BadMethodCallException
     */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException(sprintf('Method "%s" not implemented.', __METHOD__));
    }
}

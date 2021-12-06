<?php

declare(strict_types=1);

namespace Damax\Common\Pagerfanta;

use Pagerfanta\Adapter\AdapterInterface;
use Traversable;

final class CallableDecoratorAdapter implements AdapterInterface
{
    private $adapter;
    private $callback;

    public function __construct(AdapterInterface $adapter, callable $callback)
    {
        $this->adapter = $adapter;
        $this->callback = $callback;
    }

    public function getNbResults(): int
    {
        return $this->adapter->getNbResults();
    }

    public function getSlice($offset, $length): array
    {
        $items = $this->adapter->getSlice($offset, $length);
        $items = $items instanceof Traversable ? iterator_to_array($items, false) : $items;

        return array_map($this->callback, $items);
    }
}

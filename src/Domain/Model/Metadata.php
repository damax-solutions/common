<?php

declare(strict_types=1);

namespace Damax\Common\Domain\Model;

use JsonSerializable;
use OutOfBoundsException;

final class Metadata implements JsonSerializable
{
    private $values;

    public static function fromArray(array $values): self
    {
        return new self($values);
    }

    public static function kv(string $key, $value): self
    {
        return new self([$key => $value]);
    }

    public static function create(): self
    {
        return new self([]);
    }

    public function has(string $key): bool
    {
        return isset($this->values[$key]);
    }

    /**
     * @throws OutOfBoundsException
     *
     * @return mixed
     */
    public function get(string $key)
    {
        if (!isset($this->values[$key])) {
            throw new OutOfBoundsException(sprintf('Value by key "%s" not found.', $key));
        }

        return $this->values[$key];
    }

    public function all(): array
    {
        return $this->values;
    }

    public function merge(Metadata $metadata): self
    {
        $values = array_merge($this->values, $metadata->values);

        return new self($values);
    }

    public function jsonSerialize(): array
    {
        return $this->values;
    }

    private function __construct(array $values)
    {
        $this->values = $values;
    }
}

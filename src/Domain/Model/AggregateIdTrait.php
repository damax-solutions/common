<?php

declare(strict_types=1);

namespace Damax\Common\Domain\Model;

trait AggregateIdTrait
{
    private $id;

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    private function __construct(string $id)
    {
        $this->id = $id;
    }
}

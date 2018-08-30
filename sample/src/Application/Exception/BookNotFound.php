<?php

declare(strict_types=1);

namespace Sample\Application\Exception;

use RuntimeException;
use Sample\Domain\Model\BookId;

final class BookNotFound extends RuntimeException
{
    public static function byId(BookId $id): self
    {
        return new self(sprintf('Book by id "%s" not found.', (string) $id));
    }
}

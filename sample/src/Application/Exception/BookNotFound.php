<?php

declare(strict_types=1);

namespace App\Application\Exception;

use App\Domain\Model\BookId;
use RuntimeException;

final class BookNotFound extends RuntimeException
{
    public static function byId(BookId $id): self
    {
        return new self(sprintf('Book by id "%s" not found.', (string) $id));
    }
}

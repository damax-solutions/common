<?php

declare(strict_types=1);

namespace App\Application\Exception;

use App\Domain\Model\AuthorId;
use RuntimeException;

final class AuthorNotFound extends RuntimeException
{
    public static function byId(AuthorId $id): self
    {
        return new self(sprintf('Author by id "%s" not found.', (string) $id));
    }
}

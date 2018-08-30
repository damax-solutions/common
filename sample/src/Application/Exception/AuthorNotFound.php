<?php

declare(strict_types=1);

namespace Sample\Application\Exception;

use RuntimeException;
use Sample\Domain\Model\AuthorId;

final class AuthorNotFound extends RuntimeException
{
    public static function byId(AuthorId $id): self
    {
        return new self(sprintf('Author by id "%s" not found.', (string) $id));
    }
}

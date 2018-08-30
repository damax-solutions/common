<?php

declare(strict_types=1);

namespace Sample\RamseyUuid;

use Ramsey\Uuid\Uuid;
use Sample\Domain\Model\AuthorId;
use Sample\Domain\Model\BookId;
use Sample\Domain\Model\IdGenerator;

final class UuidIdGenerator implements IdGenerator
{
    public function bookId(): BookId
    {
        return BookId::fromString(Uuid::uuid4()->toString());
    }

    public function authorId(): AuthorId
    {
        return AuthorId::fromString(Uuid::uuid4()->toString());
    }
}

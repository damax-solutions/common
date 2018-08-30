<?php

declare(strict_types=1);

namespace App\RamseyUuid;

use App\Domain\Model\AuthorId;
use App\Domain\Model\BookId;
use App\Domain\Model\IdGenerator;
use Ramsey\Uuid\Uuid;

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

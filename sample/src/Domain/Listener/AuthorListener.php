<?php

declare(strict_types=1);

namespace App\Domain\Listener;

use App\Domain\Event\BookCreated;
use App\Domain\Model\AuthorRepository;

final class AuthorListener
{
    private $authors;

    public function __construct(AuthorRepository $authors)
    {
        $this->authors = $authors;
    }

    public function onBookCreated(BookCreated $event)
    {
        if (null === $author = $this->authors->byId($event->authorId())) {
            return;
        }

        $author->incrementBooksCount();

        $this->authors->update($author);
    }
}

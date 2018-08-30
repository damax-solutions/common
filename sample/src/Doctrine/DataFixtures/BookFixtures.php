<?php

declare(strict_types=1);

namespace App\Doctrine\DataFixtures;

use App\Domain\Model\Author;
use App\Domain\Model\Book;
use App\Domain\Model\IdGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class BookFixtures extends Fixture implements OrderedFixtureInterface
{
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function getOrder(): int
    {
        return 200;
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            // Author 1
            [
                'title' => 'Antony and Cleopatra',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Coriolanus',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Hamlet',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Hamlet',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Julius Caesar',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'King Lear',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Macbeth',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Othello',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'The Tragedy of Romeo and Juliet',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Timon of Athens',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Titus Andronicus',
                'author' => 'shakespeare',
            ],
            [
                'title' => 'Troilus and Cressida',
                'author' => 'shakespeare',
            ],

            // Author 2
            [
                'title' => 'The Mysterious Affair at Styles',
                'author' => 'christie',
            ],
            [
                'title' => 'The Secret Adversary',
                'author' => 'christie',
            ],
            [
                'title' => 'The Murder on the Links',
                'author' => 'christie',
            ],
            [
                'title' => 'The Man in the Brown Suit',
                'author' => 'christie',
            ],
            [
                'title' => 'The Secret of Chimneys',
                'author' => 'christie',
            ],
            [
                'title' => 'The Murder of Roger Ackroyd',
                'author' => 'christie',
            ],
            [
                'title' => 'The Big Four',
                'author' => 'christie',
            ],
            [
                'title' => 'The Mystery of the Blue Train',
                'author' => 'christie',
            ],
        ];

        foreach ($data as $item) {
            /** @var Author $author */
            $author = $this->getReference('author-' . $item['author']);

            $manager->persist(new Book($this->idGenerator->bookId(), $author->id(), $item['title']));
        }

        $manager->flush();
    }
}

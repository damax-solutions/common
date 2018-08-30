<?php

declare(strict_types=1);

namespace App\Doctrine\DataFixtures;

use App\Domain\Model\Author;
use App\Domain\Model\IdGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class AuthorFixtures extends Fixture implements OrderedFixtureInterface
{
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function getOrder(): int
    {
        return 100;
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            'shakespeare' => 'William Shakespeare',
            'christie' => 'Agatha Christie',
        ];

        foreach ($data as $alias => $fullName) {
            $author = new Author($this->idGenerator->authorId(), $fullName);

            $this->addReference('author-' . $alias, $author);

            $manager->persist($author);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 20; $i++){
            $game = new Game();
            $editor = $this->getReference(EditorFixtures::getReferenceKey(rand(0, 4)));
            $support = $this->getReference(SupportFixtures::getReferenceKey(rand(0, 2)));

            $game->setTitle($faker->text(25));
            $game->setDescription($faker->text);
            $game->setReleaseDate($faker->dateTime);
            $game->setEditor($editor);
            $game->addSupport($support);

            $manager->persist($game);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            EditorFixtures::class,
            SupportFixtures::class,
        ];
    }
}

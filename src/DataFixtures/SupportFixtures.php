<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Support;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SupportFixtures extends Fixture
{
    public static function getReferenceKey($i)
    {
        return sprintf('support_%s', $i);
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 3; $i++){
            $support = new Support();

            $support->setName($faker->text(6));
            $support->setMaker($faker->company);

            $manager->persist($support);
            $this->addReference(self::getReferenceKey($i), $support);
        }

        $manager->flush();

    }
}

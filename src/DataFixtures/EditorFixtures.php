<?php

namespace App\DataFixtures;

use App\Entity\Editor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EditorFixtures extends Fixture
{
    public static function getReferenceKey($i)
    {
        return sprintf('editor_%s', $i);
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 5; $i++){
            $editor = new Editor();
            $editor->setBuisnessName($faker->company);
            $editor->setNationality($faker->country);

            $manager->persist($editor);
            $this->addReference(self::getReferenceKey($i), $editor);
        }

        $manager->flush();
    }
}

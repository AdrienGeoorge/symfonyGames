<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    private $manager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->manager = $manager;
    }

    private function createAdmin(): void
    {
        $admin = new User();

        $admin->setEmail('admin@mail.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword(
            $this->passwordEncoder->encodePassword(
            $admin,
            'password'
        ));

        $this->manager->persist($admin);
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $this->createAdmin();

        for($i = 0; $i < 5; $i++){
            $user = new User();
            $user->setLastname($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setEmail($faker->email);
            $user->setBirthDate($faker->dateTime);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                $user,
                'password'
            ));

            $manager->persist($user);
        }

        $manager->flush();
    }
}

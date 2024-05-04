<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');

        $this->createTydooAccount($manager);

        $this->createUsers($faker, $manager);
    }

    private function createTydooAccount(ObjectManager $manager): void {
        $user = (new User())
            ->setUsername('tydoo')
            ->setEmail('thomas@tydoo.fr')
            ->setAdmin(true);
        $manager->persist($user->setPassword($this->passwordHasher->hashPassword($user, '112233')));
    }

    private function createUsers(Generator $faker, ObjectManager $manager): void {
        for ($i = 0; $i < 50; $i++) {
            $manager->persist((new User())
                ->setUsername($faker->unique()->userName())
                ->setEmail($faker->unique()->email())
                ->setPassword($faker->password()));
        }
        $manager->flush();
    }
}

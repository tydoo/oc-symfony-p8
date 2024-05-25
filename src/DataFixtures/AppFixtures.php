<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
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

        $this->createAnnonymousAccount($faker, $manager);

        $this->createUsers($faker, $manager);

        $this->createTasks($faker, $manager);
    }

    private function createTydooAccount(ObjectManager $manager): void {
        $user = (new User())
            ->setUsername('tydoo')
            ->setEmail('thomas@tydoo.fr')
            ->setAdmin(true);
        $manager->persist($user->setPassword($this->passwordHasher->hashPassword($user, '112233')));
    }

    private function createAnnonymousAccount(Generator $faker, ObjectManager $manager): void {
        $manager->persist((new User())
            ->setUsername('anonymous')
            ->setEmail('anonymous@mail.com')
            ->setPassword($faker->password()));
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

    private function createTasks(Generator $faker, ObjectManager $manager): void {
        $users = $manager->getRepository(User::class)->findAll();
        $anonymous = $manager->getRepository(User::class)->findOneBy(['username' => 'anonymous']);
        for ($i = 0; $i < 1000; $i++) {
            $manager->persist((new Task())
                ->setTitle($faker->sentence())
                ->setContent($faker->paragraph())
                ->setDone($faker->boolean(80))
                ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 year')))
                ->setUser($faker->boolean(80) ? $anonymous : $faker->randomElement($users)));
        }

        $manager->flush();
    }
}

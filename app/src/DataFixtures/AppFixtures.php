<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Entity\User;
use App\Factory\AdvertisementFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;


class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //add base user
        $admin = new User();
        $admin
            ->setLastName("admin")
            ->setfirstName("admin")
            ->setMail("admin@lebonmur.fr")
            ->setRoles([User::$ROLE_ADMIN, User::$ROLE_EDITOR, User::$ROLE_USER]);

        $plaintextPassword = "password";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $this->hasher->hashPassword(
            $admin,
            $plaintextPassword
        );
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);
        $manager->flush();

        UserFactory::createMany(10);
        TagFactory::createMany(10);
        AdvertisementFactory::createMany(30, ['tags' => self::getTags(), 'seller' => UserFactory::random()]);
    }

    private function getTags()
    {
        $rand = rand(0, 3);
        $result = [];
        for ($i = 0; $i < $rand; $i++) {
            $result[] = TagFactory::random();
        }
        return $result;
    }

    private function addBaseUsers()
    {


    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Advertisement;
use App\Factory\AdvertisementFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(100);
        AdvertisementFactory::createMany(50, ['tags' => TagFactory::new()->many(0, 3)]);
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $superadmin = new Administrateur();

        $superadmin->setFirstname($faker->firstname())
                ->setLastname($faker->lastname())
                ->setJob($faker->jobTitle())
                ->setAddress($faker->address())
                ->setcontact($faker->phoneNumber())
                ->setEmail($faker->email())
                ->setPassword($this->encoder->hashPassword($superadmin, "password"))
                ->setAvatar("avatar.png");
        $manager->persist($superadmin);
        $manager->flush();
    }
}

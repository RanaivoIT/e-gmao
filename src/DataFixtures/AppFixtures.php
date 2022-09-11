<?php

namespace App\DataFixtures;

use App\Entity\Administrateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $superadmin = new Administrateur();

        $superadmin->setFirstname("Rakoto")
                ->setLastname("Be")
                ->setJob("Admin")
                ->setAddress("NA")
                ->setcontact("NA")
                ->setEmail("NA")
                ->setPassword($this->encoder->hashPassword($superadmin, "password"))
                ->setAvatar("avatar.png");
        $manager->persist($superadmin);
        $manager->flush();
    }
}

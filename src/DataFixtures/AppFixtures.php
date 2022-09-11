<?php

namespace App\DataFixtures;

use App\Entity\Administrateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }
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
                ->setPicture("avatar.png");
        $manager->persist($superadmin);
        $manager->flush();
    }
}

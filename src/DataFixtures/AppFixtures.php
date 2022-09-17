<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Site;
use DateTimeImmutable;
use App\Entity\Demande;
use App\Entity\Colection;
use App\Entity\Operateur;
use App\Entity\Equipement;
use App\Entity\Technicien;
use App\Entity\Intervention;
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
        $faker = Factory::create('en_US');
        
        for ($i=0; $i < mt_rand(5, 10) ; $i++) { 
            $admin = new Administrateur();

            $admin->setFirstname($faker->firstname())
                    ->setLastname($faker->lastname())
                    ->setJob($faker->jobTitle())
                    ->setAddress($faker->address())
                    ->setcontact($faker->phoneNumber())
                    ->setEmail($faker->email())
                    ->setPassword($this->encoder->hashPassword($admin, "password"))
                    ->setPicture("avatar.png");
            $manager->persist($admin);
        }

        for ($i=0; $i < mt_rand(10, 20) ; $i++) { 
            $states = ['Disponible', 'Indisponible'];
            $tech = new Technicien();

            $tech->setFirstname($faker->firstname())
                    ->setLastname($faker->lastname())
                    ->setMatricule(mt_rand(1000, 9999))
                    ->setJob($faker->jobTitle())
                    ->setAddress($faker->address())
                    ->setcontact($faker->phoneNumber())
                    ->setEmail($faker->email())
                    ->setPassword($this->encoder->hashPassword($tech, "password"))
                    ->setState($states[mt_rand(0, 1)])
                    ->setPicture("avatar.png");
            $manager->persist($tech);
        }

        for ($i=0; $i < mt_rand(5, 10) ; $i++) { 
            $site = new Site();
            $site
                 ->setName($faker->company())
                 ->setAbbreviation("SITE" . $i)
                 ->setAddress($faker->address())
                 ->setContact($faker->phoneNumber())
                 ->setEmail($faker->companyEmail())
                 ->setPicture("hospitale.jpg");
 
            for ($j=0; $j < mt_rand(2, 5); $j++) { 
                $user = new Operateur();

                $user->setFirstname($faker->firstname())
                        ->setLastname($faker->lastname())
                        ->setJob($faker->jobTitle())
                        ->setAddress($faker->address())
                        ->setcontact($faker->phoneNumber())
                        ->setEmail($faker->email())
                        ->setPassword($this->encoder->hashPassword($user, "password"))
                        ->setPicture("avatar.png")
                        ->setSite($site);
                $manager->persist($user);
            }

            $colection = new Colection();
            $colection
                ->setName($faker->company())
                ->setAbbreviation("COLLECTION" . $i)
                ->setMaker($faker->company())
                ->setOrigin($faker->state())
                ->setDescription($faker->sentence())
                ->setPicture("cogs.jpg");
            
            for ($j=0; $j < mt_rand(5, 10) ; $j++) { 
                $states = ['En service', 'En panne', 'Hors service'];
                $equipement = new Equipement();
                $equipement
                    ->setColection($colection)
                    ->setSite($site)
                    ->setName($colection->getAbbreviation() . '/' . $site->getAbbreviation() . '/' . $j)
                    ->setService($faker->jobTitle())
                    ->setUsedAt(new DateTimeImmutable())
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setState($states[mt_rand(0, 2)]);


                for ($k=0; $k < mt_rand(5, 10) ; $k++) { 
                    $states = ['En attente', 'En cours', 'Soldé'];
                    $demande = new Demande();
                    $demande
                        ->setEquipement($equipement)
                        ->setDescription($faker->sentence())
                        ->setState($states[mt_rand(0, 2)])
                        ->setCreatedAt(new DateTimeImmutable());

                    $manager->persist($demande);
                }
                for ($k=0; $k < mt_rand(5, 10) ; $k++) { 
                    $types = ['CURRATIF', 'PREVENTIF'];
                    $states = ['En attente', 'En cours', 'Soldé'];
                    $intervention = new Intervention();
                    $intervention
                        ->setEquipement($equipement)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setPlannedAt(new DateTimeImmutable())
                        ->setStartedAt(new DateTimeImmutable())
                        ->setFinishedAt(new DateTimeImmutable())
                        ->setComment($faker->sentence())
                        ->setType($types[mt_rand(0, 1)])
                        ->setState($states[mt_rand(0, 2)]);
                    $manager->persist($intervention);
                }
                $manager->persist($equipement);
            }
    
            $manager->persist($colection);
 
            $manager->persist($site);
         }

        $manager->flush();
    }
}

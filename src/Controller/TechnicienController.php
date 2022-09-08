<?php

namespace App\Controller;

use App\Entity\Technicien;
use App\Repository\TechnicienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TechnicienController extends AbstractController
{
    #[Route('/techniciens', name: 'techniciens')]
    public function list(TechnicienRepository $repo): Response
    {
        $techniciens = $repo->findAll();
        return $this->render('technicien/index.html.twig', [
            'title' => 'Techniciens',
            'techniciens' => $techniciens
        ]);
    }

    #[Route('/techniciens/add', name: 'techniciens_add')]
    public function add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $tech = new Technicien();
        $form = $this->createForm(TechType::class, $tech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $tech
                ->setHash($encoder->hashPassword($tech, "password"))
                ->setPicture('avatar.png');
                
            $manager->persist($tech);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('techniciens_show', [
                'id' => $tech->getId()
            ]);
        }

        return $this->render('technicien/add.html.twig', [
            'title' => 'Techniciend - Add',
            'technicien' => $tech,
            'form' => $form->createView()
        ]);
    }
    #[Route('/techniciens/{id}', name: 'techniciens_show')]
    public function show(Technicien $technicien): Response
    {
        return $this->render('technicien/show.html.twig', [
            'title' => 'Techniciens - Show',
            'technicien' => $technicien
        ]);
    }
    #[Route('/techniciens/{id}/edit', name: 'techniciens_edit')]
    public function edit(Technicien $tech, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TechnicienType::class, $tech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($tech);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations du technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> ont été modifiées !!!"
            );

            return $this->redirectToRoute('techniciens_show', [
                'id' => $tech->getId()
            ]);
        }

        return $this->render('technicien/edit.html.twig', [
            'title' => 'Techniciens - Edit',
            'tech' => $tech,
            'form' => $form->createView()
        ]);
    }

    #[Route('/techniciens/{id}/picture', name: 'techniciens_picture')]
    public function picture(Technicien $tech, Request $request, EntityManagerInterface $manager): Response
    {
        
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "tech" . $tech->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $tech->setPicture($filename);
            }

            $manager->persist($tech);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'avatar du technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('techniciens_show', [
                'id' => $tech->getId()
            ]);
        }

        return $this->render('technicien/picture.html.twig', [
            'title' => 'Techniciens - Picture',
            'technicien' => $tech,
            'form' => $form->createView()
        ]);
        
        
    }

    #[Route('/techniciens/{id}/password', name: 'techniciens_password')]
    public function password(Technicien $technicien, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        
        $form = $this->createForm(PasswordType::class, $admin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $password =  $encoder->hashPassword($technicien, $form->get('password'));
            
            if ($password == $technicien->getPassword()) {
                $newPassword =  $form->get('newPassword');
                $manager->persist($technicien);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre mot de passe a été modifiée !!!"
                );
            }else{
                $this->addFlash(
                    'danger',
                    "L'ancien mot de passe ne correspond pas !!!"
                );
            }
            return $this->redirectToRoute('techniciens_show', [
                'id' => $technicien->getId()
            ]);
        }

        return $this->render('site/technicien/password.html.twig', [
            'title' => 'Operateurs - Password',
            'form' => $form->createView(),
            'technicien' => $technicien
        ]);
        
        
    }
}

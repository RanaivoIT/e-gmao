<?php

namespace App\Controller;

use App\Form\PictureType;
use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdministrateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdministrateurController extends AbstractController
{
    #[Route('/administrateurs', name: 'administrateurs')]
    public function index(AdministrateurRepository $repo): Response
    {

        $admins = $repo->findall();

        return $this->render('administrateur/index.html.twig', [
            'title' => 'Administrateurs',
            'administrateurs' => $admins
        ]);
    }
    #[Route('/administrateurs/add', name: 'administrateurs_add')]
    public function add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $admin = new Administrateur();
        $form = $this->createForm(AdministrateurType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $admin
                ->setPassword($encoder->hashPassword($admin, "password"))
                ->setPicture('avatar.png');
                
            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau administrateur <strong>'" . $admin->getFirstname() . ", " . $admin->getLastname() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }

        return $this->render('administrateur/add.html.twig', [
            'title' => 'Administrateurs - Add',
            'admin' => $admin,
            'form' => $form->createView()
        ]);
    }
    #[Route('/administrateurs/{id}', name: 'administrateurs_show')]
    public function show(Administrateur $admin): Response
    {
        $pictures_url = $this->getParameter('pictures_url');
        return $this->render('administrateur/show.html.twig', [
            'title' => 'Administrateur - Show',
            'admin' => $admin,
            'pictures_directory' => $pictures_url
        ]);
    }
    #[Route('/administrateurs/{id}/edit', name: 'administrateurs_edit')]
    public function edit(Administrateur $admin, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdministrateurType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de l'administrateur <strong>'" . $admin->getFirstname() . ", " . $admin->getLastname() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }

        return $this->render('administrateur/edit.html.twig', [
            'title' => 'Administrateurs - Edit',
            'admin' => $admin,
            'form' => $form->createView()
        ]);
    }
    #[Route('/administrateurs/{id}/picture', name: 'administrateurs_picture')]
    public function picture(Administrateur $admin, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "admin" . $admin->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $admin->setAvatar($filename);
            }

            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'avatar du administrateur <strong>'" . $admin->getFirstname() . ", " . $admin->getLastname() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_show', [
                'id' => $admin->getId()
            ]);
        }

        return $this->render('administrateur/picture.html.twig', [
            'title' => 'Admin - Admin',
            'admin' => $admin,
            'form' => $form->createView()
        ]);
    }
    #[Route('/administrateurs/{id}/password', name: 'administrateurs_password')]
    public function password(Administrateur $admin, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(PasswordType::class, $admin);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password =  $encoder->hashPassword($admin, $form->get('password'));
            
            if ($password == $admin->getPassword()) {
                $newPassword =  $form->get('newPassword');
                $manager->persist($admin);
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
            return $this->redirectToRoute('administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }

        return $this->render('administrateur/password.html.twig', [
            'title' => 'Administrateurs - Password',
            'form' => $form->createView(),
            'admin' => $admin
        ]);
    }
}

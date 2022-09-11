<?php

namespace App\Controller;

use App\Entity\Operateur;
use App\Form\PictureType;
use App\Form\PasswordType;
use App\Form\OperateurType;
use App\Repository\OperateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OperateurController extends AbstractController
{
    #[Route('/operateurs', name: 'operateurs')]
    public function a_operateur_list(OperateurRepository $repo): Response
    {
        $operateurs = $repo->findAll();
        return $this->render('operateur/index.html.twig', [
            'title' => 'Operateurs',
            'operateurs' => $operateurs
        ]);
    }
    #[Route('/operateurs/add', name: 'operateurs_add')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $operateur = new Operateur();
        $form = $this->createForm(OperateurType::class, $operateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $operateur
                ->setPassword($encoder->hashPassword($operateur, "password"))
                ->setPicture('avatar.png');
                
            $manager->persist($operateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau operateur <strong>'" . $operateur->getFirstname() . ", " . $operateur->getLastname() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('operateurs_show', [
                'id' => $operateur->getId()
            ]);
        }

        return $this->render('operateur/add.html.twig', [
            'title' => 'Operateurs - Add',
            'operateur' => $operateur,
            'form' => $form->createView()
        ]);
    }
    #[Route('/operateurs/{id}', name: 'operateurs_show')]
    public function show(Operateur $operateur): Response
    {
        return $this->render('operateur/show.html.twig', [
            'title' => 'Operateurs - Show',
            'operateur' => $operateur
        ]);
    }
    #[Route('/operateurs/{id}/edit', name: 'operateurs_edit')]
    #[Security("is_granted('ROLE_OPERATEUR') and user===operateur")]
    public function edit(Operateur $operateur, Request $request, EntityManagerInterface $manager): Response
    {
        
        $form = $this->createForm(OperateurType::class, $operateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($operateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de l'operateur <strong>'" . $operateur->getFirstname() . ", " . $operateur->getLastname() . "'</strong> ont été modifiées !!!"
            );

            return $this->redirectToRoute('operateurs_show', [
                'id' => $operateur->getId()
            ]);
        }

        return $this->render('operateur/edit.html.twig', [
            'title' => 'Operateurs - Edit',
            'operateur' => $operateur,
            'form' => $form->createView()
        ]);
    }

    #[Route('/operateurs/{id}/picture', name: 'operateurs_picture')]
    #[Security("is_granted('ROLE_OPERATEUR') and user===operateur")]
    public function picture(Operateur $operateur, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "operateur" . $operateur->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $operateur->setPicture($filename);
            }

            $manager->persist($operateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre picture <strong>'" . $operateur->getFirstname() . ", " . $operateur->getLastname() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('operateurs_show', [
                'id' => $operateur->getId()
            ]);
        }

        return $this->render('operateur/picture.html.twig', [
            'title' => 'Operateurs - Picture',
            'operateur' => $operateur,
            'form' => $form->createView()
        ]);
        
        
    }

    #[Route('/operateurs/{id}/password', name: 'operateurs_password')]
    #[Security("is_granted('ROLE_OPERATEUR') and user===operateur")]
    public function password(Operateur $operateur, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(PasswordType::class, $admin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $password =  $encoder->hashPassword($operateur, $form->get('password'));
            
            if ($password == $operateur->getPassword()) {
                $newPassword =  $form->get('newPassword');
                $manager->persist($operateur);
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
            return $this->redirectToRoute('operateurs_show', [
                'id' => $operateur->getId()
            ]);
        }

        return $this->render('operateur/password.html.twig', [
            'title' => 'Operateurs - Password',
            'form' => $form->createView(),
            'operateur' => $operateur
        ]);
        
    }

}

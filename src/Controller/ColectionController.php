<?php

namespace App\Controller;

use App\Entity\Colection;
use App\Form\ColectionType;
use App\Repository\ColectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColectionController extends AbstractController
{
    #[Route('/colections', name: 'colections')]
    public function index(ColectionRepository $repo): Response
    {
        $colections = $repo->findAll();
        return $this->render('colection/index.html.twig', [
            'title' => 'Collections',
            'colections' => $colections
        ]);
    }
    #[Route('/colections/add', name: 'colections_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $colection = new Colection();

        $form = $this->createForm(ColectionType::class, $colection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $colection
                ->setPicture('cogs.jpg');
                
            $manager->persist($colection);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle collection <strong>'" . $colection->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('colections_show', [
                'id' => $colection->getId()
            ]);
        }

        return $this->render('colection/add.html.twig', [
            'title' => 'Collections - Add',
            'colection' => $colection,
            'form' => $form->createView()
        ]);
    }

    #[Route('/colections/{id}', name: 'colections_show')]
    public function show(Colection $colection): Response
    {
        return $this->render('colection/show.html.twig', [
            'title' => 'Collection - Show',
            'colection' => $colection
        ]);
    }
    #[Route('/colections/{id}/edit', name: 'colections_edit')]
    public function edit(Colection $colection, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ColectionType::class, $colection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($colection);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de la collection <strong>'" . $colection->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_colection_show', [
                'id' => $colection->getId()
            ]);
        }

        return $this->render('colection/edit.html.twig', [
            'title' => 'Collection - Edit',
            'colection' => $colection,
            'form' => $form->createView()
        ]);
    }
    #[Route('/colections/{id}/picture', name: 'colections_picture')]
    public function picture(Colection $colection, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "colection" . $colection->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $colection->setPicture($filename);
            }

            $manager->persist($colection);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'image du colection <strong>'" . $colection->getName() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('colections_show', [
                'id' => $colection->getId()
            ]);
        }

        return $this->render('colection/picture.html.twig', [
            'title' => 'Collections',
            'colection' => $colection,
            'form' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Organe;
use App\Form\OrganeType;
use App\Entity\Colection;
use App\Repository\OrganeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrganeController extends AbstractController
{
    #[Route('/organes', name: 'organes')]
    public function index(OrganeRepository $repo): Response
    {
        $organes = $repo->findAll();
        return $this->render('organe/index.html.twig', [
            'title' => 'Organes',
            'organes' => $organes,
        ]);
    }
    #[Route('/organes/add', name: 'organes_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $organe = new Organe();
        $form = $this->createForm(OrganeType::class, $organe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            foreach ($organe->getPieces() as $piece) {
                $piece->setOrgane($organe);
                $manager->persist($piece);
            }

            $manager->persist($organe);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle organe <strong>'" . $organe->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('organes_show', [
                'id' => $organe->getId()
            ]);
        }

        return $this->render('organe/add.html.twig', [
            'title' => 'Organes - Add',
            'organe' => $organe,
            'form' => $form->createView()
        ]);
    }
    #[Route('/organes/{id}', name: 'organes_show')]
    public function show(Organe $organe): Response
    {
        return $this->render('organe/show.html.twig', [
            'title' => 'Organe - Show',
            'organe' => $organe
        ]);
    }
    #[Route('/organes/{id}/edit', name: 'organes_edit')]
    public function edit(Organe $organe, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(OrganeType::class, $organe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($organe->getPieces() as $piece) {
                $piece->setOrgane($organe);
                $manager->persist($piece);
            }
            $manager->persist($organe);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'organe <strong>'" . $organe->getName(). "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('organes_show', [
                'id' => $organe->getId()
            ]);
        }

        return $this->render('organe/edit.html.twig', [
            'title' => 'Organes - Edit',
            'organe' => $organe,
            'form' => $form->createView()
        ]);
    }

    #[Route('/organes/{id}/remove', name: 'organes_remove')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function remove(Organe $colection, EntityManagerInterface $manager): Response
    {
        $manager->remove($colection);
        $manager->flush();
        $this->addFlash(
            'success',
            "Vous avez supprimé l'organe " . $colection->getId() . " !!!"
        );
        return $this->redirectToRoute('organes');
    }
    
}

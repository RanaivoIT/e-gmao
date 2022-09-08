<?php

namespace App\Controller;

use DateTime;
use App\Entity\Intervention;
use App\Form\InterventionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends AbstractController
{
    #[Route('/interventions', name: 'interventions')]
    public function index(InterventionRepository $repo): Response
    {
        $interventions = $repo->findall();

        return $this->render('intervention/index.html.twig', [
            'title' => 'Interventions',
            'interventions' => $interventions
        ]);
    }
    #[Route('/interventions/add', name: 'interventions_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $intervention = new Intervention();
        $intervention->setcreatedAt(new DateTime());
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $intervention->setCreatedAt(new DateTime());

            $manager->persist($intervention);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle intervention <strong>'" . $intervention->getEquipement()->getName() ."'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('interventions_show', [
                'id' => $intervention->getId()
            ]);
        }

        return $this->render('intervention/add.html.twig', [
            'title' => 'Intervention - Add',
            'intervention' => $intervention,
            'form' => $form->createView()
        ]);
    }
    #[Route('/interventions/{id}', name: 'interventions_show')]
    public function show(Intervention $intervention): Response
    {
        return $this->render('intervention/show.html.twig', [
            'title' => 'Interventions -Show',
            'intervention' => $intervention
        ]);
    }
    #[Route('/interventions/{id}/edit', name: 'interventions_edit')]
    public function edit(Intervention $intervention, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($intervention);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations d' intervention <strong>'" . $intervention->getEquipement()->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('intervention_show', [
                'id' => $intervention->getId()   
            ]);
        }

        return $this->render('intervention/edit.html.twig', [
            'title' => 'Interventions - Edit',
            'intervention' => $intervention,
            'form' => $form->createView()
        ]);
    }
}

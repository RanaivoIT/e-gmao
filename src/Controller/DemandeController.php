<?php

namespace App\Controller;

use DateTime;
use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemandeController extends AbstractController
{
    #[Route('/demandes', name: 'demandes')]
    public function index(DemandeRepository $repo): Response
    {
        $demandes = $repo->findAll();
        return $this->render('demande/index.html.twig', [
            'title' => 'Demandes',
            'demandes' => $demandes
        ]);
    }
    #[Route('/demandes/add/', name: 'demandes_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $demande = new Demande();
        
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $demande->setCreatedAt(new DateTime());

            $manager->persist($demande);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle demande sur l'equipement <strong>'" . $demande->getEquipement()->getName() . "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('demandes_show', [
                'id' => $demande->getId()
            ]);
        }

        return $this->render('demande/add.html.twig', [
            'title' => 'Demandes - Add',
            'demande' => $demande,
            'form' => $form->createView()
        ]);
    }
    #[Route('/demandes/{id}', name: 'demandes_show')]
    public function show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
            'title' => 'Demandes - Show',
            'demande' => $demande
        ]);
    }
    #[Route('/demandes/{id}/edit', name: 'demandes_edit')]
    public function edit(Demande $demande, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($demande);
            $manager->flush();

            $this->addFlash(
                'success',
                "La demande sur l'equipement <strong>'" . $demande->getEquipement()->getName() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('demandes_show', [
                'id' => $demande->getId()
            ]);
        }

        return $this->render('demande/add.html.twig', [
            'title' => 'Demandes - Edit',
            'demande' => $demande,
            'form' => $form->createView()
        ]);
    }
}

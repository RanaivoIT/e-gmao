<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemandeController extends AbstractController
{
    #[Route('/demandes', name: 'demandes')]
    #[Security("is_granted('ROLE_ADMINISTRATEUR') or is_granted('ROLE_OPERATEUR')")]
    public function index(DemandeRepository $repo): Response
    {
        $demandes = $repo->findAll();

        return $this->render('demande/index.html.twig', [
            'title' => 'Demandes',
            'demandes' => $demandes
        ]);
    }

    #[Route('/demandes/add/', name: 'demandes_add')]
    #[IsGranted('ROLE_OPERATEUR')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $demande = new Demande();
        
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $demande->setCreatedAt(new DateTimeImmutable());

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
    #[IsGranted('ROLE_OPERATEUR')]
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

        return $this->render('demande/edit.html.twig', [
            'title' => 'Demandes - Edit',
            'demande' => $demande,
            'form' => $form->createView()
        ]);
    }
}

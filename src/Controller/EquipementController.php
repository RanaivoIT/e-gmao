<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Equipement;
use App\Form\EquipementType;
use App\Repository\EquipementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipementController extends AbstractController
{
    #[Route('/equipements', name: 'equipements')]
    public function index(EquipementRepository $repo): Response
    {

        $equipements = $repo->findall();
        if ($this->isGranted('ROLE_OPERATEUR')) {
            $equipements = $repo->findBySite($this->getUser()->getSite());
        }
        return $this->render('equipement/index.html.twig', [
            'title' => 'Equipements',
            'equipements' => $equipements
        ]);
    }
    #[Route('/equipements/add', name: 'equipements_add')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $equipement = new Equipement();
        $equipement->setUsedAt(new DateTimeImmutable());
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $equipement->setCreatedAt(new DateTimeImmutable());

            $manager->persist($equipement);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle equipement <strong>'" . $equipement->getName() . "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('equipements_show', [
                'id' => $equipement->getId()
            ]);
        }

        return $this->render('equipement/add.html.twig', [
            'title' => 'Equipements',
            'equipement' => $equipement,
            'form' => $form->createView()
        ]);
    }
    #[Route('/equipements/{id}', name: 'equipements_show')]
    public function show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'title' => 'Equipements - Show',
            'equipement' => $equipement
        ]);
    }
    #[Route('/equipements/{id}/remove', name: 'equipements_remove')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function remove(Document $colection, EntityManagerInterface $manager): Response
    {
        $manager->remove($colection);
        $manager->flush();
        $this->addFlash(
            'success',
            "Vous avez supprimé l'equipement " . $colection->getId() . " !!!"
        );
        return $this->redirectToRoute('equipements');
    }
    #[Route('/equipements/{id}/edit', name: 'equipements_edit')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function edit(Equipement $equipement, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($equipement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de l'equipement <strong>'" . $equipement->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('equipements_show', [
                'id' => $equipement->getId()
            ]);
        }

        return $this->render('equipement/edit.html.twig', [
            'title' => 'Equipement - Edit',
            'equipement' => $equipement,
            'form' => $form->createView()
        ]);
    }

    #[Route('/equipements/{id}/imprimer', name: 'equipements_imprimer')]
    public function imprimer(Equipement $equipement): Response
    {
        return $this->render('equipement/imprimer.html.twig', [
            'title' => 'Equipement - Imprimer',
            'equipement' => $equipement
        ]);
    }

}

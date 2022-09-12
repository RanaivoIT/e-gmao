<?php

namespace App\Controller;

use App\Repository\DemandeRepository;
use App\Repository\EquipementRepository;
use App\Repository\TechnicienRepository;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(DemandeRepository $demandeRepo, InterventionRepository $interventionRepo, EquipementRepository $equipementRepo, TechnicienRepository $techRepo): Response
    {
        if ($this->isGranted('ROLE_ADMINISTRATEUR')) {
            return $this->render('dashboard/administrateur.html.twig', [
                'title' => 'Dashboard',
                'demandes' => $demandeRepo->findAll(),
                'demandes1' => $demandeRepo->findByState('En attente'),
                'demandes2' => $demandeRepo->findByState('En cours'),
                'demandes3' => $demandeRepo->findByState('Soldé'),
                'interventions' => $interventionRepo->findAll(),
                'interventions1' => $interventionRepo->findByState('En attente'),
                'interventions2' => $interventionRepo->findByState('En cours'),
                'interventions3' => $interventionRepo->findByState('Soldé'),
                'equipements' => $equipementRepo->findAll(),
                'equipements1' => $equipementRepo->findByState('En service'),
                'equipements2' => $equipementRepo->findByState('En panne'),
                'equipements3' => $equipementRepo->findByState('Hors service'),
                'techniciens' => $techRepo->findAll(),
                'techniciens1' => $techRepo->findByState('Disponible'),
                'techniciens2' => $techRepo->findByState('Indisponible'),
            ]);
        }
        if ($this->isGranted('ROLE_TECHNICIEN')) {
            return $this->render('dashboard/technicien.html.twig', [
                'title' => 'Dashboard',
                'interventions' => $interventionRepo->findByTech($this->getUser()),
                'interventions1' => $interventionRepo->findByTechAndState($this->getUser(),'En attente'),
                'interventions2' => $interventionRepo->findByTechAndState($this->getUser(),'En cours'),
                'interventions3' => $interventionRepo->findByTechAndState($this->getUser(),'Soldé'),
            ]);
        }
        if ($this->isGranted('ROLE_OPERATEUR')) {
            return $this->render('dashboard/operateur.html.twig', [
                'title' => 'Dashboard',
                'demandes' => $demandeRepo->findBySite($this->getUser()->getSite()),
                'demandes1' => $demandeRepo->findBySiteAndState($this->getUser()->getSite(), 'En attente'),
                'demandes2' => $demandeRepo->findBySiteAndState($this->getUser()->getSite(), 'En cours'),
                'demandes3' => $demandeRepo->findBySiteAndState($this->getUser()->getSite(), 'Soldé'),
                'interventions' => $interventionRepo->findBySite($this->getUser()->getSite()),
                'interventions1' => $interventionRepo->findBySiteAndState($this->getUser()->getSite(),'En attente'),
                'interventions2' => $interventionRepo->findBySiteAndState($this->getUser()->getSite(),'En cours'),
                'interventions3' => $interventionRepo->findBySiteAndState($this->getUser()->getSite(),'Soldé'),
                'equipements' => $equipementRepo->findBySite($this->getUser()->getSite()),
                'equipements1' => $equipementRepo->findBySiteAndState($this->getUser()->getSite(), 'En service'),
                'equipements2' => $equipementRepo->findBySiteAndState($this->getUser()->getSite(), 'En panne'),
                'equipements3' => $equipementRepo->findBySiteAndState($this->getUser()->getSite(), 'Hors service')
            ]);
        }
        return null;
    }
}

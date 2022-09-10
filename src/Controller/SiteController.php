<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Form\PictureType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    #[Route('/sites', name: 'sites')]
    public function index(SiteRepository $repo): Response
    {

        $sites = $repo->findall();

        return $this->render('site/index.html.twig', [
            'title' => 'Sites',
            'sites' => $sites
        ]);
    }
    #[Route('/sites/add', name: 'sites_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $site = new Site();

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $site
                ->setPicture('site.jpg');
                
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau site <strong>'" . $site->getName(). "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('sites_show', [
                'id' => $site->getId()
            ]);
        }

        return $this->render('site/add.html.twig', [
            'title' => 'Sites - Add',
            'site' => $site,
            'form' => $form->createView()
        ]);
    }
    #[Route('/sites/{id}', name: 'sites_show')]
    public function show(Site $site): Response
    {   
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site
        ]);
    }
    #[Route('/sites/{id}/edit', name: 'sites_edit')]
    public function edit(Site $site, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations du site <strong>'" . $site->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('sites_show', [
                'id' => $site->getId()
            ]);
        }

        return $this->render('site/edit.html.twig', [
            'title' => 'Site - Edit',
            'site' => $site,
            'form' => $form->createView()
        ]);
    }
    #[Route('/sites/{id}/picture', name: 'sites_picture')]
    public function change_picture(Site $site, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "site" . $site->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $site->setPicture($filename);
            }

            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'image du site <strong>'" . $site->getName() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_site_show', [
                'id' => $site->getId()
            ]);
        }

        return $this->render('site/picture.html.twig', [
            'title' => 'Site - Picture',
            'site' => $site,
            'form' => $form->createView()
        ]);
    }
}

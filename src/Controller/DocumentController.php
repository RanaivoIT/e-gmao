<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentController extends AbstractController
{
    #[Route('/documents', name: 'documents')]
    public function index(DocumentRepository $repo): Response
    {
        $documents = $repo->findAll();
        return $this->render('colection/document/index.html.twig', [
            'title' => 'Documents',
            'documents' => $documents,
        ]);
    }
    #[Route('/documents/add', name: 'documents_add')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $document = new Document();

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($document);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau document <strong>'" . $document->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('documents_show', [
                'id' => $document->getId()
            ]);
        }

        return $this->render('colection/document/add.html.twig', [
            'title' => 'Document - Add',
            'document' => $document,
            'form' => $form->createView()
        ]);
    }
    #[Route('/documents/{id}', name: 'documents_show')]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'title' => 'Documents - Show',
            'document' => $document
        ]);
    }
    #[Route('/documents/{id}/remove', name: 'documents_remove')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function remove(Document $colection, EntityManagerInterface $manager): Response
    {
        $manager->remove($colection);
        $manager->flush();
        $this->addFlash(
            'success',
            "Vous avez supprimé le document " . $colection->getId() . " !!!"
        );
        return $this->redirectToRoute('documents');
    }
    #[Route('/documents/{id}/edit', name: 'documents_edit')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function edit(Document $document, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($document);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le document <strong>'" . $document->getName(). "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('documents_show', [
                'id' => $document->getId()
            ]);
        }

        return $this->render('document/edit.html.twig', [
            'title' => 'Documents - Edit',
            'document' => $document,
            'form' => $form->createView()
        ]);
    }
}

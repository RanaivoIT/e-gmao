<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentController extends AbstractController
{
    #[Route('/documents', name: 'documents')]
    public function index(DocumentRepository $repo): Response
    {
        $documents = $repo->findAll();
        return $this->render('document/index.html.twig', [
            'title' => 'Documents',
            'documents' => $documents,
        ]);
    }
    #[Route('/documents/add', name: 'documents_add')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(DocumentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $document = new Document();
            $doc = $form->get('data')->getData();
            if ($doc) {
                $filename = "document" . mt_rand(1000000, 9999999) . "." . $doc->guessExtension();
                try {
                    $doc->move(
                        $this->getParameter('docs_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $document->setData($filename);
                $document->setName($form->get('name')->getData());
                $document->setColection($form->get('colection')->getData());
            }

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

        return $this->render('document/add.html.twig', [
            'title' => 'Document - Add',
            'form' => $form->createView()
        ]);
    }

    #[Route('/documents/{id}', name: 'documents_show')]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'title' => 'Documents - Show',
            'url_doc'=> $this->getParameter('docs_url') . $document->getData(),
            'document' => $document
        ]);
    }

    #[Route('/documents/{id}/pdf', name: 'documents_pdf')]
    public function pdf(Document $document): Response
    {
        return new BinaryFileResponse($this->getParameter('docs_directory') . '/' . $document->getData());
    }

    #[Route('/documents/{id}/edit', name: 'documents_edit')]
    #[IsGranted('ROLE_ADMINISTRATEUR')]
    public function edit(Document $document, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(DocumentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $doc = $form->get('data')->getData();
            if ($doc) {
                $filename = $document->getData();
                try {
                    $doc->move(
                        $this->getParameter('docs_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $document->setData($filename);
                $document->setName($form->get('name')->getData());
                $document->setColection($form->get('colection')->getData());
            }

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

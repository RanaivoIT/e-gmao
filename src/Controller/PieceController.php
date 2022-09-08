<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Form\PieceType;
use App\Repository\PieceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PieceController extends AbstractController
{
    #[Route('/pieces', name: 'pieces')]
    public function index(PieceRepository $repo): Response
    {
        $pieces = $repo->findAll();
        return $this->render('piece/index.html.twig', [
            'title' => 'Piece - List',
            'pieces' => $pieces,
        ]);
    }
    #[Route('/pieces/add', name: 'pieces_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $piece = new Piece();

        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($piece);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle piece <strong>'" . $piece->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('pieces_show', [
                'id' => $piece->getId()
            ]);
        }

        return $this->render('piece/add.html.twig', [
            'title' => 'Pieces - Add',
            'piece' => $piece,
            'form' => $form->createView()
        ]);
    }
    #[Route('/pieces/{id}', name: 'pieces_show')]
    public function show(Piece $piece): Response
    {
        return $this->render('piece/show.html.twig', [
            'title' => 'Pieces - Show',
            'piece' => $piece
        ]);
    }
    #[Route('/pieces/{id}/edit', name: 'pieces_edit')]
    public function edit(Piece $piece, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(OrganeType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($piece);
            $manager->flush();

            $this->addFlash(
                'success',
                "La piece <strong>'" . $piece->getName(). "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('pieces_show', [
                'id' => $piece->getId()
            ]);
        }

        return $this->render('piece/edit.html.twig', [
            'title' => 'Pieces - Edit',
            'piece' => $piece,
            'form' => $form->createView()
        ]);
    }
}

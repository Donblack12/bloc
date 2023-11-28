<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository; // Assurez-vous d'importer le CommentRepository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualitePolitiqueController extends AbstractController
{
    private $actualites = [
        ['titre' => 'Actualité 1', 'contenu' => 'Contenu actualité 1'],
        ['titre' => 'Actualité 2', 'contenu' => 'Contenu actualité 2'],
        // Ajoutez d'autres actualités si nécessaire
    ];

    /**
     * @Route("/actualite-politique", name="actualite_politique")
     */
    public function index(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('actualite_politique');
        }

        // Utilisation de l'injection de dépendance pour le repository
        $comments = $commentRepository->findAll();

        return $this->render('actualite_politique/actualite_politique.html.twig', [
            'actualites' => $this->actualites,
            'commentForm' => $form->createView(), // Assurez-vous que cette clé correspond à celle utilisée dans le template Twig
            'comments' => $comments,
        ]);
    }
}

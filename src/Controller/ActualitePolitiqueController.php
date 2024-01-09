<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ActualitePolitiqueController extends AbstractController
{
    private $actualites = [
        ['titre' => 'Actualité 1', 'contenu' => 'Contenu actualité 1'],
        ['titre' => 'Actualité 2', 'contenu' => 'Contenu actualité 2'],
        // Ajoutez d'autres actualités si nécessaire
    ];

    /**
     * @Route("/actualite-politique/{titre}", name="actualite_politique")
     */
    public function index(Request $request, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {
        // Création et gestion du formulaire de commentaire
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setSujetId('rdc'); // Définition du sujet_id comme 'rdc'
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirection pour rafraîchir et montrer le nouveau commentaire
            return $this->redirectToRoute('actualite_politique');;
        }

        // Récupération des commentaires pour 'rdc'
        $comments = $commentRepository->findBy(['sujetId' => 'rdc']);

        // Envoi des données au template
        return $this->render('actualite_politique/actualite_politique.html.twig', [
            'commentForm' => $form->createView(),
            'comments' => $comments,
        ]);
    }
}


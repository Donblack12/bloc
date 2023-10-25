<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response
    {
        return $this->render('actualite_politique/actualite_politique.html.twig', [
            'actualites' => $this->actualites,
        ]);
    }
}



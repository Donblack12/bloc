<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualitePolitiqueController extends AbstractController
{
    /**
     * @Route("/actualite-politique", name="actualite_politique")
     */
    public function index(): Response
    {
        $actualites = [
            ['titre' => 'Actualité 1', 'contenu' => 'Contenu actualité 1'],
            ['titre' => 'Actualité 2', 'contenu' => 'Contenu actualité 2'],
            // ... ajoutez d'autres actualités si nécessaire
        ];

        return $this->render('actualite_politique/actualite_politique.html.twig', [
            'actualites' => $actualites,
        ]);

    }
}

<?php

namespace App\Controller;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Form\LoginFormType;

// Ajoutez cet import

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $formAuthenticator, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if the user is already logged in
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('actualite_politique');
        }

        $form = $this->createForm(LoginFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, on utilise l'authentificateur pour vérifier les informations de l'utilisateur
            $authResult = $userAuthenticator->authenticateUser(
                $form->getData(),
                $formAuthenticator,
                $request
            );

            if ($authResult) {
                // Si l'authentification réussit, redirigez l'utilisateur
                return $this->redirectToRoute('route_apres_connexion');
            } else {
                // Sinon, ajoutez un message d'erreur
                $error = "Identifiants incorrects. Veuillez réessayer ou créer un compte.";
            }
        }

        // Render the login form with potential error
        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'loginForm' => $form->createView(),
        ]);
    }

}
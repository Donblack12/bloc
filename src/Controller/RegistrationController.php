<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginFormAuthenticator; // Remplacez ceci par le nom de votre propre Authenticator

class RegistrationController extends AbstractController
{
private EmailVerifier $emailVerifier;

public function __construct(EmailVerifier $emailVerifier)
{
$this->emailVerifier = $emailVerifier;
}

#[Route('/registration', name: 'app_register')]
public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, SessionInterface $session, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $formAuthenticator): Response
{
$user = new User();
$form = $this->createForm(RegistrationFormType::class, $user, [
'csrf_protection' => false  // CSRF protection disabled
]);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
// Hash the password
$user->setPassword(
$passwordHasher->hashPassword(
$user,
$form->get('plainPassword')->getData()
)
);

$entityManager->persist($user);
$entityManager->flush();

// Authentification de l'utilisateur
$userAuthenticator->authenticateUser(
$user,
$formAuthenticator,
$request
);

// Redirection vers une route après l'inscription et la connexion
return $this->redirectToRoute('actualite_politique');
}

return $this->render('registration/register.html.twig', [
'registrationForm' => $form->createView(),
]);
}
}

<?php
namespace App\Security;

use App\Entity\User; // Assurez-vous que c'est le bon chemin vers votre entité User
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator implements AuthenticatorInterface
{
    private RouterInterface $router;
    private EntityManagerInterface $entityManager;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request): ?bool
    {
        // Activez cet authenticator pour les routes de connexion et d'inscription
        return in_array($request->attributes->get('_route'), ['app_login', 'app_register']);
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (!$email || !$password) {
            throw new CustomUserMessageAuthenticationException('Email et mot de passe sont requis.');
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException('Aucun utilisateur trouvé avec cet email.');
        }

        // Ici, vous pouvez ajouter la logique pour vérifier le mot de passe
        // Si le mot de passe ne correspond pas, lancez une exception

        $userBadge = new UserBadge($user->getUserIdentifier());

        return new SelfValidatingPassport($userBadge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($request->attributes->get('_route') === 'app_login') {
            return new Response($this->router->generate('actualite_politique'));
        }

        // Redirection après l'inscription si nécessaire
        // Par exemple, retourner à la page d'accueil ou à une page de bienvenue

        return new Response($this->router->generate('une_autre_route'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Gérer l'échec de l'authentification si nécessaire
        // Vous pouvez rediriger vers la page de connexion avec un message d'erreur
        return new Response($exception->getMessageKey(), Response::HTTP_FORBIDDEN);
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return new UsernamePasswordToken($passport->getUser(), $firewallName, $passport->getUser()->getRoles());
    }
}

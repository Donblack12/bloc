<?php
namespace App\Security;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
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

class LoginFormAuthenticator implements AuthenticatorInterface
{
private RouterInterface $router;

public function __construct(RouterInterface $router)
{
$this->router = $router;
}

public function supports(Request $request): ?bool
{
// Activez cet authenticator pour une route spécifique ou une condition
return $request->attributes->get('_route') === 'app_register';
}

public function authenticate(Request $request): Passport
{
// Récupération de l'utilisateur de la session
$user = $request->getSession()->get('user');
if (!$user instanceof UserInterface) {
throw new AuthenticationException('No user found in session.');
}

// Création d'un UserBadge avec l'identifiant de l'utilisateur
$userBadge = new UserBadge($user->getUserIdentifier());

return new SelfValidatingPassport($userBadge);
}

public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
// Redirection après une authentification réussie
return new Response($this->router->generate('actualite_politique'));
}

public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
{
// Gérer l'échec de l'authentification si nécessaire
return new Response($exception->getMessageKey(), Response::HTTP_FORBIDDEN);
}

public function createToken(Passport $passport, string $firewallName): TokenInterface
{
// Création et retour d'un TokenInterface
return new UsernamePasswordToken($passport->getUser(), $firewallName, $passport->getUser()->getRoles());
}
}

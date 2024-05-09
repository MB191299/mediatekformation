<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant l'authentification OAuth.
 *
 * @author mb
 */
class OAuthController extends AbstractController
{
    /**
     * Redirige l'utilisateur vers la page de connexion OAuth.
     *
     * @Route("/oauth/login", name="oauth_login")
     */
    public function index(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('keycloak')->redirect([], []);
    }

    /**
     * Gère la réponse de l'authentification OAuth.
     *
     * @Route("/oauth/callback", name="oauth_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
    }

    /**
     * Déconnecte l'utilisateur.
     *
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}

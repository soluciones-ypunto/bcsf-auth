<?php
/**
 * Created by javier
 * Date: 22/05/19
 * Time: 18:22
 */

namespace Ypunto\Authentication\Security\Authentication;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\Resolver\OrmResolver;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ypunto\Authentication\Security\Authentication\Authenticator\OAuth2Authenticator;
use Ypunto\Authentication\Security\Authentication\Identifier\LocalUserIdentifier;

/**
 * Class AuthenticationServiceProvider
 *
 * @package Ypunto\Authentication\Security\Authentication
 */
class AuthenticationServiceProvider implements AuthenticationServiceProviderInterface
{

    /**
     * Returns an authentication service instance.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     *
     * @return \Authentication\AuthenticationServiceInterface
     */
    public function getAuthenticationService(ServerRequestInterface $request, ResponseInterface $response)
    {
        $service = new AuthenticationService();

        $fields = [
            'username' => 'usuario',
            'password' => 'password',
        ];
        $resolver = [
            'className' => OrmResolver::class,
            'userModel' => 'Usuarios',
            'finder' => 'auth',
        ];

        $service->loadIdentifier(LocalUserIdentifier::class, [
            'fields' => $fields,
            'resolver' => $resolver,
        ]);

        // Load the authenticators, you want session first
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator(OAuth2Authenticator::class);

        return $service;
    }
}
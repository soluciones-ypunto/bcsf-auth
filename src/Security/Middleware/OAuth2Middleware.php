<?php
/**
 * Created by javier
 * Date: 22/05/19
 * Time: 17:33
 */

namespace Ypunto\Authentication\Security\Middleware;

use Authentication\UrlChecker\UrlCheckerTrait;
use Cake\Core\InstanceConfigTrait;
use Cake\Routing\Router;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ypunto\Authentication\Security\Service\OAuth2Service;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Class OAuth2Middleware
 * @package Ypunto\Authentication\Security\Middleware
 */
class OAuth2Middleware
{
    use InstanceConfigTrait;
    use UrlCheckerTrait;

    /**
     * @var OAuth2Service|null
     */
    protected $_service = null;

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'authUrl' => null,
        'loginRedirect' => null,
        'urlChecker' => 'Authentication.Default',
    ];

    /**
     * OAuth2Middleware constructor.
     *
     * @param OAuth2Service $service
     * @param array         $config
     */
    public function __construct(OAuth2Service $service, array $config = [])
    {
        $this->setConfig($config);
        $this->_service = $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param                        $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if (!$this->_getUrlChecker()->check(
            $request,
            $this->getConfig('authUrl'),
            (array)$this->getConfig('urlChecker')
        )) {
            return $next($request, $response);
        }

        /** @var \Cake\Http\Session $session */
        $session = $request->getAttribute('session');
        $queryParams = $request->getQueryParams();

        if (empty($queryParams['code'])) {
            $loginRedirect = $queryParams['redirect'] ?? urlencode($this->_getDefaultRedirectUrl());
            $authorizationUrl = $this->_service->getAuthorizationUrl(['state' => base64_encode($loginRedirect)]);
            $session->write('oauth2state', $this->_service->getState());

            return new RedirectResponse($authorizationUrl);
        }

        if (empty($queryParams['state']) || $queryParams['state'] !== $session->read('oauth2state')) {
            throw new InvalidArgumentException(__('Missing or invalid state'));
        }

        $this->_service->fetchAccessToken($queryParams['code']);
        $request = $request->withAttribute('oauth2', $this->_service);

        return $next($request, $response);
    }

    /**
     * @return string
     */
    private function _getDefaultRedirectUrl()
    {
        $loginRedirect = $this->getConfig('loginRedirect');

        if (empty($loginRedirect)) {
            throw new \RuntimeException(__('Missing config Ypunto/Authentication.OAuth2.Middleware.loginRedirect'));
        }

        return (string)Router::url($loginRedirect, true);
    }
}

<?php
/**
 * Created by javier
 * Date: 22/05/19
 * Time: 18:32
 */

namespace Ypunto\Authentication\Security\Middleware;

use Cake\Core\InstanceConfigTrait;
use Cake\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RestrictedAccessMiddleware
 * @package Ypunto\Authentication\Security\Middleware
 */
class RestrictedAccessMiddleware
{
    use InstanceConfigTrait;

    const ALL = 'all';

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'deny' => self::ALL,
        'allow' => [],
        'redirectQueryParam' => 'redirect',
        'loginUrl' => ['_name' => 'login'],
        'routeBuilder' => [Router::class, 'url'],
    ];

    /**
     * RestrictedAccessMiddleware constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        /** @var \Authentication\AuthenticationServiceInterface $authentication */
        $authentication = $request->getAttribute('authentication');

        if ($authentication && $authentication->getResult()->isValid()) {
            return $next($request, $response);
        }

        $loginUrl = $this->getConfig('loginUrl');
        $loginUrl = is_string($loginUrl) ? $loginUrl : Router::url($loginUrl);
        $url = $this->getRedirectUrl($loginUrl, $request);

        $redirectResponse = $response
            ->withStatus(302)
            ->withHeader('Location', $url);

        if ($this->_config['deny'] === self::ALL) {

            $this->_config['allow'][] = $loginUrl;

            foreach ((array)$this->_config['allow'] as $allowedRoute) {
                if ($this->matchingRoute($allowedRoute, $request)) {
                    return $next($request, $response);
                }
            }

            return $redirectResponse;
        }

        if ($this->_config['allow'] === self::ALL) {
            foreach ((array)$this->_config['deny'] as $deniedRoute) {
                if ($this->matchingRoute($deniedRoute, $request)) {
                    return $redirectResponse;
                }
            }

            return $next($request, $response);
        }

        return $redirectResponse;
    }

    /**
     * Returns redirect URL.
     *
     * @param string $target Redirect target.
     * @param \Psr\Http\Message\ServerRequestInterface $request Request instance.
     * @return string
     */
    protected function getRedirectUrl($target, ServerRequestInterface $request)
    {
        $param = $this->getConfig('redirectQueryParam');
        if ($param === null) {
            return $target;
        }

        $uri = $request->getUri();
        $requestedUri = $uri->__toString();

        if (!empty($uri->base)) { // cakephp specific
            $requestedUri = str_replace($uri->getPath(), $uri->base . $uri->getPath(), $requestedUri);
        }

        if ($target === substr($requestedUri, -strlen($target))) {
            return $target;
        }

        $query = urlencode($param) . '=' . urlencode($requestedUri);

        if (strpos($target, '?') !== false) {
            $query = '&' . $query;
        } else {
            $query = '?' . $query;
        }

        return $target . $query;
    }

    /**
     * @param string|array|callable $route
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    protected function matchingRoute($route, $request)
    {
        if (is_callable($route)) {
            return $route($request);
        }

        if (is_string($route)) {
            return $route === ($request->getUri()->base ?? '') . $request->getUri()->getPath();
        }

        try {
            $_cakeRoute = Router::url($route);
            if ($_cakeRoute) {
                return $_cakeRoute === ($request->getUri()->base ?? '') . $request->getUri()->getPath();
            }
        } catch (\Exception $e) {}

        return false;
    }
}

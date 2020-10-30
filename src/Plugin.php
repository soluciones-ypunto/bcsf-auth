<?php

namespace Ypunto\Authentication;

use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Ypunto\Authentication\Security\Authentication\AuthenticationServiceProvider;
use Ypunto\Authentication\Security\Middleware\OAuth2Middleware;
use Ypunto\Authentication\Security\Middleware\RestrictedAccessMiddleware;
use Ypunto\Authentication\Security\Service\OAuth2Service;

/**
 * Plugin for Ypunto\Authentication
 */
class Plugin extends BasePlugin
{

    use InstanceConfigTrait;

    /**
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @param PluginApplicationInterface $app
     */
    public function bootstrap(PluginApplicationInterface $app)
    {
        $app->addPlugin('Authentication');

        Configure::load('Ypunto/Authentication.app');

        $this->setConfig(Configure::consume('__ypunto_authentication_default_configs'));
        $this->setConfig(Configure::read('Ypunto/Authentication'));
    }

    /**
     * Add routes for the plugin.
     *
     * The default implementation of this method will include the `config/routes.php` in the plugin if it exists. You
     * can override this method to replace that behavior.
     *
     * @param RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes($routes)
    {
        parent::routes($routes);

        $_params = [
            'plugin' => 'Ypunto/Authentication',
            '_namePrefix' => 'auth.'
        ];

        $routes->scope('/auth', $_params, function (RouteBuilder $routes) {
            $routes->connect('/login', ['controller' => 'Authentication', 'action' => 'login'], ['_name' => 'login']);
            $routes->connect('/logout', ['controller' => 'Authentication', 'action' => 'logout'], ['_name' => 'logout']);

            $routes->fallbacks(DashedRoute::class);
        });
    }

    /**
     * @param MiddlewareQueue $middleware
     *
     * @return MiddlewareQueue
     */
    public function middleware($middleware)
    {
        $oauth2Service = new OAuth2Service($this->getConfig('OAuth2.Service'));
        $authenticationServiceProvider = new AuthenticationServiceProvider();

        $middleware
            ->add(new OAuth2Middleware($oauth2Service, $this->getConfig('OAuth2.Middleware')))
            ->add(new AuthenticationMiddleware($authenticationServiceProvider))
            ->add(new RestrictedAccessMiddleware([
                'allow' => $this->getConfig('allowUnauthenticated'),
                'loginUrl' => $this->getConfig('loginUrl')
            ]))
        ;

        return $middleware;
    }
}

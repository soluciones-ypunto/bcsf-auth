<?php
/**
 * Created by javier
 * Date: 22/05/19
 * Time: 16:19
 */

use Psr\Http\Message\ServerRequestInterface;

return [

    /**
     * default plugin configurations
     */
    '__ypunto_authentication_default_configs' => [

        /**
         *
         */
        'loginUrl' => ['_name' => 'auth.login'],

        /**
         *
         */
        'allowUnauthenticated' => [
            // debugkit plugin
            function (ServerRequestInterface $request) {
                $params = $request->getAttribute('params') + ['plugin' => false];
                return $params['plugin'] === 'DebugKit';
            },
        ],

        /**
         * OAuth2 layer settings
         */
        'OAuth2' => [

            /**
             *
             */
            'enabled' => false,

            /**
             *
             */
            'Service' => [
                'clientId' => 'com.example.app',
                'clientSecret' => '****',
                'redirectUri' => 'http://app.example.com/oauth2-check',

                'urlAuthorize' => 'http://sso.example.com/authorize',
                'urlAccessToken' => 'http://sso.example.com/access-token',
                'urlResourceOwnerDetails' => 'http://sso.example.com/resource',
            ],

            /**
             *
             */
            'Middleware' => [
                'authUrl' => '/oauth2-check',
            ],
        ],
    ],
];

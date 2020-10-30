<?php
/**
 * Created by javier
 * Date: 22/05/19
 * Time: 17:33
 */

namespace Ypunto\Authentication\Security\Service;

use Cake\Core\InstanceConfigTrait;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

/**
 * Class OAuth2Service
 *
 * @package Ypunto\Authentication\Security\Service
 */
class OAuth2Service
{
    use InstanceConfigTrait;

    const RESULT_SUCCESS = 'success';

    const RESULT_FAIL = 'fail';

    /**
     * @var AbstractProvider
     */
    protected $_provider;

    /**
     * @var AccessTokenInterface|AccessToken
     */
    protected $_accessToken = null;

    /**
     * @var string
     */
    protected $_result;

    /**
     * @var string
     */
    protected $_error = '';

    /**
     * @var array
     */
    protected $_defaultConfig = [
        /**
         * Estos parámetros son del cliente, es el id de aplicación, la pass y la url de vuelta
         * esto tiene que ser parametrizable y configurable en cada proyecto donde se instale el cliente
         */
        'clientId'                => '',
        'clientSecret'            => '',
        'redirectUri'             => '',

        /**
         * Estos son parámetros fijos del servidor, tenemos que implementar nuestro propio provider que
         * ya tenga estos valores establecidos como fijos ya que son conocidos
         */
        'urlAuthorize'            => '/oauth2/authorize',
        'urlAccessToken'          => '/oauth2/access-token',
        'urlResourceOwnerDetails' => '/oauth2/resource',
    ];

    /**
     * OAuth2Service constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setConfig($config);
        $this->_provider = new GenericProvider($this->getConfig());
    }

    /**
     * @param $code
     */
    public function fetchAccessToken($code)
    {
        try {
            $this->_accessToken = $this->_provider->getAccessToken('authorization_code', ['code' => $code,]);
            $this->_result = self::RESULT_SUCCESS;
        } catch (IdentityProviderException $e) {
            $this->_error = json_encode($e->getResponseBody());
            $this->_result = self::RESULT_FAIL;
        }
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function getAuthorizationUrl($options = [])
    {
        return $this->_provider->getAuthorizationUrl($options);
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->_provider->getState();
    }

    /**
     * @return ResourceOwnerInterface
     */
    public function getResourceOwner()
    {
        return $this->_provider->getResourceOwner($this->_accessToken);
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->_error;
    }
}
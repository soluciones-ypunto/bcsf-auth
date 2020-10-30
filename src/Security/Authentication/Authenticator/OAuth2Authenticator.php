<?php
/**
 * Created by javier
 * Date: 9/05/19
 * Time: 9:17
 */

namespace Ypunto\Authentication\Security\Authentication\Authenticator;

use Authentication\Authenticator\AbstractAuthenticator;
use Authentication\Authenticator\Result;
use Authentication\Identifier\IdentifierInterface;
use Cake\Log\Log;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LogLevel;
use Ypunto\Authentication\Security\Authentication\Authenticator\Identity\ExternalInfoInterface;
use Ypunto\Authentication\Security\Service\OAuth2Service;

/**
 * Class OAuth2Authenticator
 *
 * @package Admin\Security\Authentication\Authenticator
 */
class OAuth2Authenticator extends AbstractAuthenticator
{

    /**
     * Authenticate a user based on the request information.
     *
     * @param ServerRequestInterface $request  Request to get authentication information from.
     * @param ResponseInterface      $response A response object that can have headers added.
     *
     * @return \Authentication\Authenticator\ResultInterface Returns a result object.
     */
    public function authenticate(ServerRequestInterface $request, ResponseInterface $response)
    {
        /** @var OAuth2Service $service */
        $service = $request->getAttribute('oauth2');

        if ($service === null || ($service->getResult() !== OAuth2Service::RESULT_SUCCESS)) {
            return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND, ($service ? [$service->getError()] : []));
        }

        try {
            $usuario = $service->getResourceOwner()->toArray();

            return new Result($usuario, Result::SUCCESS);

        } catch (Exception $e) {
            Log::write(LogLevel::INFO, $e->getMessage());
            return new Result(null, Result::FAILURE_IDENTITY_NOT_FOUND, (array)$e->getMessage());
        }
    }
}

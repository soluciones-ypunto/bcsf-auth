<?php
/**
 * Created by javier
 * Date: 9/05/19
 * Time: 9:18
 */

namespace Ypunto\Authentication\Security\Authentication\Identifier;

use Authentication\Identifier\PasswordIdentifier;

class LocalUserIdentifier extends PasswordIdentifier
{

    /**
     * Identifies an user or service by the passed credentials
     *
     * @param array $credentials Authentication credentials
     *
     * @return \ArrayAccess|array|null
     */
    public function identify(array $credentials)
    {
        if (!isset($credentials[self::CREDENTIAL_USERNAME])) {
            return null;
        }

        $identity = $this->_findIdentity($credentials[self::CREDENTIAL_USERNAME]);

        return $identity;
    }
}
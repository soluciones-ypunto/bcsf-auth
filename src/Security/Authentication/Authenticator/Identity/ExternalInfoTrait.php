<?php
/**
 * Created by javier
 * Date: 14/05/19
 * Time: 13:09
 */

namespace Ypunto\Authentication\Security\Authentication\Authenticator\Identity;

/**
 * Implementation of ExternalInfoInterface
 *
 * @package Admin\Security\Authentication\Authenticator\Identity
 */
trait ExternalInfoTrait
{
    /**
     * @var array
     */
    protected $_externalInfo = [];

    /**
     * @param array $externalInfo
     */
    public function setExternalInfo(array $externalInfo)
    {
        $this->_externalInfo = $externalInfo;
    }

    /**
     * @return array
     */
    public function getExternalInfo()
    {
        return $this->_externalInfo;
    }
}
<?php
/**
 * Created by javier
 * Date: 14/05/19
 * Time: 13:07
 */

namespace Ypunto\Authentication\Security\Authentication\Authenticator\Identity;

interface ExternalInfoInterface
{
    /**
     * @param array $externalInfo
     *
     * @return void
     */
    public function setExternalInfo(array $externalInfo);

    /**
     * @return array
     */
    public function getExternalInfo();
}
<?php
/**
 * Created by javier
 * Date: 10/05/19
 * Time: 12:26
 */

namespace Ypunto\Authentication\Controller;

/**
 * Class Oauth2CheckController
 * @package Ypunto\Authentication\Controller
 */
class Oauth2CheckController extends AppController
{
    /**
     * @return \Cake\Http\Response|null
     * @todo Tenemos que ver cómo removemos esto, actualmente se ejecuta porque llega acá
     *       luego de efectuar todo el proceso de forma correcta
     *
     */
    public function index()
    {
        $redirectUrl = urldecode(base64_decode($this->request->getQuery('state')));

        return $this->redirect($redirectUrl);
    }
}

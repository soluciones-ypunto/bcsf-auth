<?php
/**
 * Created by javier
 * Date: 22/05/19
 * Time: 18:40
 */

namespace Ypunto\Authentication\Controller;

class AuthenticationController extends AppController
{

    /**
     * @return \Cake\Http\Response|void
     */
    public function login()
    {
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        $this->Flash->success(__('Has salido correctamente!'));

        $return = $this->request
            ->getAttribute('authentication')
            ->clearIdentity($this->request, $this->response);

        return $this
            ->setRequest($return['request'])
            ->setResponse($return['response'])
            ->redirect(['_name' => 'auth.login']);
    }
}

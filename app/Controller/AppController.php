<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {

    public $components = array(
        'RequestHandler',
        'Session',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email')
                )
            ),
            'loginRedirect' => HOME_URL,
            'logoutRedirect' => HOME_URL,
            'authError' => 'Please log in.',
        ),
        //'DebugKit.Toolbar',
    );

    public $helpers = array(
        'Html','Form', 'Paginator'
    );

    public function beforeRender(){

        $user = $this->Auth->User();
        $this->set('isLoggedIn', $user != false);
    }

    /**
     * Ajax form response
     */
    protected function _ajaxFormResponse($message=false, $isError=false, $redirectUri=false){

        $this->set(array(
            '_serialize' => array(
                'isError',
                'message',
                'redirectUri'
            ),
            'isError' => $isError,
            'message' => __($message),
            'redirectUri' => $redirectUri
        ));
    }

    /**
     * Display a generic flash message
     */
    protected function _setFlash($message, $type='error'){
        $this->Session->setFlash(__($message),'default',array(),$type);
    }
}

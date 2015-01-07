<?php

class LoungesController extends AppController {

    public $helpers = array('Html', 'Form');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'landing',
            'create',
            'search'
        ));
    }

    /**
     * Landing page for private lounges
     */
    public function landing(){

        $this->layout = 'lounge_landing';
    }

    /**
     * Search for a lounge page
     */
    public function search(){

        $searchTerm = $this->request->data('search');

        $results = array();
        if(strlen($searchTerm) >= 2){
            $results = $this->Lounge->find('all',array(
                'contain' => array(
                    'LoungeUser' => array(
                        'User',
                        'conditions' => array(
                            'user_relationship' => 'patient'
                        )
                    )
                ),
                'conditions' => array(
                    'OR' => array(
                        'Lounge.subdomain LIKE' => "%$searchTerm%",
                        'Lounge.name LIKE' => "%$searchTerm%"
                    )
                )
            ));
        }

        $this->set(array(
            'results' => $results
        ));
    }

    public function create(){

        $this->loadModel('User');

        $errorMessage = false;
        $isError = false;
        $redirectUri = false;

        $name = $this->request->data('name');
        $address = $this->request->data('address');
        $relationship = $this->request->data('relationship');
        $firstName = $this->request->data('firstname');
        $lastName = $this->request->data('lastname');
        $email = $this->request->data('email');
        $password = $this->request->data('password');

        $lounge = array(
            'subdomain' => $address,
            'name' => $name,
            'privacy' => 'private'
        );

        $user = array(
            'role' => 'user',
            'email' => $email,
            'password' => $password,
            'first_name' => $firstName,
            'last_name' => $lastName,
        );

        $this->Lounge->set($lounge);
        if(!$this->Lounge->validates()){
            $isError = true;
            $errorMessage = $this->Lounge->validationErrorsAsString();
        }

        $this->User->set($user);
        if(!$this->User->validates()){
            $isError = true;
            $errorMessage .= $this->User->validationErrorsAsString();
        }

        if(!$isError){

            $saved = false;
            $dataSource = $this->Lounge->getDataSource();
            try {
                $dataSource->begin();

                if(!$this->Lounge->save($lounge))
                    throw new Exception($this->Lounge->validationErrorsAsString());

                if(!$this->User->save($user))
                    throw new Exception($this->User->validationErrorsAsString());

                $loungeUser = array(
                    'lounge_id' => $this->Lounge->id,
                    'user_id' => $this->User->id,
                    'user_relationship' => $relationship,
                    'user_role' => 'admin'
                );

                if(!$this->Lounge->LoungeUser->save($loungeUser)){
                    throw new Exception($this->Lounge->LoungeUser->validationErrorsAsString());
                }

                $dataSource->commit();
                $saved = true;
            }
            catch(Exception $e){
                $errorMessage = $e->getMessage();
                $dataSource->rollback();
            }

            if(!$saved){
                $isError = true;
                //$errorMessage = 'We encountered an unexpected error creating your lounge.';
            }
            else {

                //Log user in
                $user['id'] = $this->User->id;
                $this->Auth->login($user);

                //Redirect user to the lounge
                $redirectUri = "http://$name." . SITE_DOMAIN;
            }
        }

        $this->_ajaxFormResponse($errorMessage, $isError, $redirectUri);
    }

    /**
    * View lounge associated with currently logged in user
    */
    public function myLounge(){

        $this->loadModel('LoungeUser');

        $reenableLoungeOwnedBehavior = false;
        if($this->LoungeUser->Behaviors->enabled('LoungeOwned')){
            $this->LoungeUser->Behaviors->disable('LoungeOwned');
            $reenableLoungeOwnedBehavior = true;
        }

        $userId  = $this->Auth->User('id');
        $loungeMembership = $this->LoungeUser->find('first',array(
            'contain' => array(
                'Lounge'
            ),
            'conditions' => array(
                'LoungeUser.user_id' => $userId
            )
        ));

        if($reenableLoungeOwnedBehavior){
            $this->LoungeUser->Behaviors->enable('LoungeOwned');
        }

        if(!empty($loungeMembership)){
            $this->redirect("http://" . $loungeMembership['Lounge']['subdomain'] . "." . SITE_DOMAIN);
        }
        else {
            $this->redirect('/');
        }
    } 
}

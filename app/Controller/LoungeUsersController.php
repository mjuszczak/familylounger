<?php

class LoungeUsersController extends AppController {

    public $components = array(
        'LoungeArea'
    );

    public $helpers = array('Html', 'Form');

    /**
     * Handles authorization for lounge specific actions
     */
    public function isLoungeAuthorized($loungePrivacy, $userLoungeRole){

        if($userLoungeRole == 'admin')
            return true;

        return false;
    }

    /**
     * Callback
     */
    public function beforeFilter(){

        parent::beforeFilter();

        $this->set('title_for_layout','People');
    }

    /**
     * View list of lounge members
     */
    public function index(){

        $this->loadModel('LoungeInvitation');
        $this->loadModel('LoungeRequest');

        //Retrieve 6 people
        $this->paginate = array(
            'contain' => array(
                'User'
            ),
            'limit' => 6,
            'order' => array(
                'User.created' => 'asc'
            )
        );

        $loungeUsers = $this->paginate('LoungeUser');
        $this->set('loungeUsers',$loungeUsers); 

        //Retrieve lounge invite and request counts
        $inviteCount = $this->LoungeInvitation->find('count', array(
            'conditions' => array(
                'LoungeInvitation.status' => 'pending'
            )
        ));
        $requestCount = $this->LoungeRequest->find('count', array(
            'conditions' => array(
                'LoungeRequest.status' => 'pending'
            )
        ));
        $this->set(array(
            'inviteCount' => $inviteCount,
            'requestCount' => $requestCount
        ));
    }

    /**
     * Edit a user's role or relationship
     */
    public function edit($userId){

        $loungeUser = $this->LoungeUser->find('first', array(
            'contain' => array(
                'User'
            ),
            'conditions' => array(
                'LoungeUser.user_id' => $userId
            )
        ));

        if(empty($loungeUser)){
            $this->_setFlash('This user does not exist or is not a member of this lounge');
            $this->redirect('/people');
        }

        $this->set(array(
            'loungeUser' => $loungeUser,
            'roles' => LoungeUser::$roles,
            'relationships' => LoungeUser::$relationships
        ));
        
        if($this->request->is('put')){
        
            $this->LoungeUser->id = $loungeUser['LoungeUser']['id'];
            $result = $this->LoungeUser->save(
                $this->request->data,
                true,
                array(
                    'user_relationship',
                    'user_role'
                )
            );

            if($result){
                $this->_setFlash('User membership updated', 'success');
                $this->redirect('/people');
            }
            else {
                $this->setFlash($this->LoungeUser->validationErrorsAsString());
            }
        }
        else {
            $this->request->data = $loungeUser;
        }
    }

    /**
     * Unfriend a lounge member
     */
    public function delete($userId){

        $loungeUser = $this->LoungeUser->find('first', array(
            'contains' => array(),
            'conditions' => array(
                'LoungeUser.user_id' => $userId
            )
        ));

        if(empty($loungeUser)){
            $this->_setFlash('This user does not exist or is not a member of this lounge');
            $this->redirect('/people');
        }

        if($this->LoungeUser->delete($loungeUser['LoungeUser']['id'])){
            $this->_setFlash('User membership removed', 'success');
            $this->redirect('/people');
        }
        else {
            $this->_setFlash('We experienced a problem removing this user\'s membership');
        }

    }
}

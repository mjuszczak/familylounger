<?php

class LoungeRequestsController extends AppController {

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
        
        $this->LoungeArea->allow(array(
            'add'
        ));

        $this->set('title_for_layout', 'Requests');
    }

    /**
     * A list of lounge requests
     */
    public function index(){

        //Retrieve most recent X invitations
        $this->paginate = array(
            'contain' => array(
                'User'
            ),
            'conditions' => array(
                'LoungeRequest.status' => 'pending'
            ),
            'limit' => 7,
            'order' => array(
                'LoungeRequest.updated' => 'desc'
            )
        );

        $requests = $this->paginate('LoungeRequest');
        $this->set('requests', $requests);
    }

    /**
     * Add a lounge request
     *
     * This action is available to anonymous users. Keep that in mind
     * while coding or modifying it.
     */
    public function add(){

        $this->loadModel('User');
        $this->loadModel('LoungeUser');
        $this->loadModel('LoungeInvitation');

        $isError = false;
        $message = 'test';
        $redirectUri = false;

        $userId = $this->Auth->User('id');

        if(empty($userId))
            throw new BadRequestException('You must be logged in to request an invite');

        if(!$this->request->is('post'))
            throw new BadRequestException('This request must be issued via the POST method');

        //Checks:
        // 1) user is not already a member of this lounge
        // 2) user does not already have a pending lounge request

        if($this->LoungeUser->isMember($userId)){
            $isError = true;
            $message = 'You are already a member of this lounge';
        }
        elseif($this->LoungeRequest->hasPendingRequest($userId)){
            $isError = true;
            $message = 'You already have a request pending';
        }
        else {
            $loungeRequest = array_merge(
                $this->request->data['LoungeRequest'],
                array('user_id' => $userId)
            );
            if($this->LoungeRequest->save($loungeRequest)){
                $message = 'Request sent';
            }
            else {
                $isError = true;
                $message = $this->LoungeRequest->validationErrorsAsString();
            }
        }

        return $this->_ajaxFormResponse(
            $message,
            $isError,
            $redirectUri
        );
    }

    public function accept($requestId){

        $request = $this->LoungeRequest->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'LoungeRequest.id' => $requestId,
                'LoungeRequest.status' => 'pending'
            )
        ));

        if(empty($request)){
            $this->_setFlash('This request does not exist');
            $this->redirect('/people/requests');
        }

        $loungeUser = array(
            'user_id' => $request['LoungeRequest']['user_id'],
            'user_relationship' => 'friend',
            'user_role' => 'user'
        );

        if($this->LoungeUser->save($loungeUser)){
            $this->LoungeRequest->id = $request['LoungeRequest']['id'];
            $this->LoungeRequest->saveField('status', 'accepted');
        }
        else {
            $this->_setFlash('We encountered a problem linking this user to your lounge');
        }

        $this->redirect('/people/requests');
    }

    public function ignore($requestId){

        $request = $this->LoungeRequest->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'LoungeRequest.id' => $requestId,
                'LoungeRequest.status' => 'pending'
            )
        ));

        if(empty($request)){
            $this->_setFlash('This request does not exist');
            $this->redirect('/people/requests');
        }

        $this->LoungeRequest->id = $request['LoungeRequest']['id'];
        $this->LoungeRequest->saveField('status', 'ignored'); 

        $this->redirect('/people/requests');
    }
}

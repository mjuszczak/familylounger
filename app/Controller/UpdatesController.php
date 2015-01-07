<?php

class UpdatesController extends AppController {

    public $components = array(
        'LoungeArea'
    );

    public $helpers = array(
        'Html', 'Form','Gravatar'
    );

    /**
     * Handles authorization for lounge specific actions
     */
    public function isLoungeAuthorized($loungePrivacy, $userLoungeRole){

        if($userLoungeRole == 'admin')
            return true;

        return false;
    }

    /*
    * List of most recent updates
    */
    public function index(){

        //Retrieve most recent updates
        $this->paginate = array(
            'contain' => array(
                'User'
            ),
            'limit' => 7,
            'order' => array(
                'Update.created' => 'desc'
            )
        );

        $updates = $this->paginate('Update');
        $this->set('updates',$updates);
    }
}

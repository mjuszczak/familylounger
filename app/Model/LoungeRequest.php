<?php

App::uses('LoungeUser', 'Model');

class LoungeRequest extends AppModel {

    public $actsAs = array(
        'LoungeOwned',
    );

    public $belongsTo = array(
        'Lounge',
        'User'
    );

    public $validate = array(
        'lounge_id' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Lounge ID is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Lounge ID cannot be empty'
            ),
            'isNumeric' => array(
                'rule' => 'numeric',
                'message' => 'Lounge ID must be an integer'
            ),
            'validForeignKey' => array(
                'rule' => array('isValidForeignKey'),
                'message' => 'Lounge does not exist'
            )
        ),
        'user_id' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'User ID is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'User ID cannot be empty'
            ),
            'isNumeric' => array(
                'rule' => 'numeric',
                'message' => 'User ID must be an integer'
            ),
            'validForeignKey' => array(
                'rule' => 'isValidForeignKey',
                'message' => 'User does not exist'
            )
        ),
    );

    /**
     * Callback
     */
    public function beforeSave($options=array()){

        if(!parent::beforeSave($options))
            return false;

         //Strip HTML/Javascript from message
        if(isset($this->data[$this->alias]['message']))
            $this->data[$this->alias]['message'] = strip_tags($this->data[$this->alias]['message']);

        return true;
    }

    /**
     * Return an associative array of relationships
     */
    public function getRelationshipOptions(){

        return LoungeUser::$relationships;
    }

    /**
     * Test whether this user indetified by the supplied email address
     * has a pending lounge request
     */
    public function hasPendingRequest($userId){

        $request = $this->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'LoungeRequest.user_id' => $userId,
                'LoungeRequest.status' => 'pending'
            )
        ));

        return !empty($request);
    }
}

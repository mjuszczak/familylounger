<?php

App::uses('LoungeUser', 'Model');

class LoungeInvitation extends AppModel {

    public $actsAs = array(
        'LoungeOwned',
    );

    public $belongsTo = array(
        'Lounge',
        'User' => array(
            'foreignKey' => 'invited_by'
        )
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
                'rule' => 'isValidForeignKey',
                'message' => 'Lounge does not exist'
            )
        ),
        'invited_by' => array(
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
        'first_name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'First name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'First name cannot be empty'
            ),
        ),
        'email' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Email is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Email cannot be empty'
            ),
            'validEmail' => array(
                'rule' => 'email',
                'message' => 'Please enter a valid email address'
            )
        ),
        'user_relationship' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'User relationship is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'User relationship cannot be empty'
            ),
            'valid' => array(
                'rule' => 'validUserRelationship',
                'message' => 'Please select a relationship'
            )
        ),
        'accept_token' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Token is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Token cannot be empty'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Need a unique accept code'
            )
        )
    );

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
     * Test whether the user identified by the supplied email
     * address has a pending invitation.
     */
    public function hasPendingInvitation($email){

        $invitation = $this->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'LoungeInvitation.email' => $email,
                'LoungeInvitation.status' => 'pending'
            )
        ));

        return !empty($invitation);
    }
}

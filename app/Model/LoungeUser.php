<?php

class LoungeUser extends AppModel {

    public static $relationships = array(
        'caretaker' => 'Caretaker',
        'family' => 'Family Member',
        'friend' => 'Friend',
        'patient' => 'Patient'
    );

    public static $roles = array(
        'user' => 'User',
        'admin' => 'Administrator'
    );

    public $actsAs = array(
        'LoungeOwned',
        'LoungeUpdate'
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
                'rule' => array('isValidForeignKey'),
                'message' => 'User does not exist'
            )
        ),
        'user_role' => array(
             'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'User role is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'User role cannot be empty'
            ),
            'valid' => array(
                'rule' => 'validUserRole',
                'message' => 'Invalid user role'
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
        )
    );

    /**
     * Validate a user role
     */
    public function validUserRole($input){

        $suppliedRole = $input['user_role'];

        if(isset(self::$roles[$suppliedRole]))
            return true;

        return false;
    }

    /**
     * Check if a user is a member of a lounge
     */
    public function isMember($userId, $loungeId=false){

        if(empty($userId))
            return false;

        $conditions = array(
            'LoungeUser.user_id' => $userId
        );

        if($loungeId !== false)
            $conditions['LoungeUser.lounge_id'] = $loungeId;

        $loungeUser = $this->find('first', array(
            'conditions' => $conditions
        ));

        return !empty($loungeUser);
    }

    /**
     * Callback sets the update message
     */
    public function loungeUpdateCallback($action){

        $resource_url = false;
        $description = "";

        if($action == 'delete' || $action == 'update'){
            //Don't record updates for these actions
            return false;
        }
        else {
            $loungeUserId = $this->id;
            $userId = isset($this->data[$this->alias]['user_id']) ?
                $this->data[$this->alias]['user_id'] :
                $this->field('user_id', array('LoungeUser.id' => $loungeUserId));

            $resource_url = "/users/profile/$userId";
            $description = "joined the lounge.";
        }

        return array($resource_url,$description);
    }
}

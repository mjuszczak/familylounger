<?php

class Update extends AppModel {

    public $name = 'Update';

    public $actsAs = array(
        'LoungeOwned'
    );

    public $belongsTo = array(
        'Lounge',
        'User',
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
        'model' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'The resource\'s URL is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The resource\'s URL cannot be empty'
            ),
        ),
        'model_id' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'The resource\'s URL is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The resource\'s URL cannot be empty'
            ),
        ), 
        'resource_url' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'The resource\'s URL is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The resource\'s URL cannot be empty'
            ),    
        ), 
        'description' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A description is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The description cannot be empty'
            ),    
        ),
        'action' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'An action is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Action cannot be empty'
            ),
            'valid' => array(
                'rule' => '/^create|update|delete$/',
                'message' => 'Invalid action'
            )
        ),
    ); 
}

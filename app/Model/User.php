<?php

App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {

    public $name = 'User';

    public $virtualFields = array(
        'full_name' => 'CONCAT(User.first_name, " ", User.last_name)',
    );

    public $hasMany = array(
        'LoungeUser'
    );

    public $validate = array(
        'email' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'An email address is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Email address cannot be empty'
            ),
            'validEmail' => array(
                'rule' => 'email',
                'message' => 'The supplied email address is invalid'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email address is associated with an existing user\'s account'
            )
        ), 
        'password' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A password is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Passwords cannot be empty'
            ),
            'minLength' => array(
                'rule' => array('minLength', 4),
                'message' => 'Passwords must be at least 4 characters long'
            )
        ),
        'first_name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A first name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'First name cannot be empty'
            )
        ),
        'last_name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A last name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Last name cannot be empty'
            )
        ),
    );

    public function beforeSave($options = array()) {

        if(!parent::beforeSave($options))
            return false;

        if(isset($this->data[$this->alias]['email']))
            $this->data[$this->alias]['email'] = strtolower($this->data[$this->alias]['email']);

        if(isset($this->data[$this->alias]['password']))
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);

        if(isset($this->data[$this->alias]['first_name']))
            $this->data[$this->alias]['first_name'] = ucfirst(strtolower($this->data[$this->alias]['first_name']));

        if(isset($this->data[$this->alias]['last_name']))
            $this->data[$this->alias]['last_name'] = ucfirst(strtolower($this->data[$this->alias]['last_name']));

        return true;
    }
}
?>

<?php

class Medication extends AppModel {

    public $actsAs = array(
	    'LoungeOwned',
    );

    public $belongsTo = array(
        'Lounge'
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
        'name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The name cannot be empty'
            ), 
        ),
        'dosage' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A dosage is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The dosage cannot be empty'
            ),
        ),
    );

    public function beforeSave($options){

        if(!parent::beforeSave($options))
            return false;

        //Strip HTML/Javascript
        if(isset($this->data[$this->alias]['name']))
            $this->data[$this->alias]['description'] = strip_tags($this->data[$this->alias]['name']);
        if(isset($this->data[$this->alias]['description']))
            $this->data[$this->alias]['description'] = strip_tags($this->data[$this->alias]['description']);

        return true;
    }
}

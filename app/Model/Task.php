<?php

class Task extends AppModel {

    public $actsAs = array(
	    'LoungeOwned',
        'LoungeUpdate'
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
        'description' => array(
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
        'completed' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                'message' => 'Completed must be a valid boolean'
            )
        )
    );

     public function beforeSave($options){

        if(!parent::beforeSave($options))
            return false;

        //Strip HTML/Javascript
        if(isset($this->data[$this->alias]['description']))
            $this->data[$this->alias]['description'] = strip_tags($this->data[$this->alias]['description']);

        return true;
    }

    public function loungeUpdateCallback($action){

        $resource_url = false;
        $description = "";

        if($action == 'delete'){
            return false;
        }
        else {
            $taskId = $this->id;
            $taskDescr = isset($this->data[$this->alias]['description']) ?
                $this->data[$this->alias]['description'] :
                $this->field('description', array('Task.id' => $taskId));

            $resource_url = "/tasks";

            if($action == 'create'){
                $description = "created a new task <strong>$taskDescr</strong>.";
            }
            else {
                $description = "updated task <strong>$taskDescr</strong>.";
            }
        }

        return array($resource_url,$description);
    }
}

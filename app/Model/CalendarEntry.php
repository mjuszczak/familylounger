<?php
class CalendarEntry extends AppModel {

    public $useTable = 'calendars';

    public $actsAs = array(
        'LoungeOwned',
        'LoungeUpdate'
    );

    public $belongsTo = array(
        'User','Lounge'
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
        'title' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A title is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The title cannot be empty'
            ),
        ),
        'start' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Start date/time is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Start date/time cannot be empty'
            ),
            'validDatetime' => array(
                'rule' => 'validDatetime',
                'message' => 'Please supply a valid start date/time'
            ),
        ),
        'end' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'End date/time is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'End date/time cannot be empty'
            ),
            'validDatetime' => array(
                'rule' => 'validDatetime',
                'message' => 'Please supply a valid end date/time'
            ),
        ), 
    );

    /**
     * Validate a date/time
     * 
     * Differs from CakePHP's native datetime validator
     * in that it just verifies the datetime can be parsed
     * by strtotime().
     */
    public function validDatetime($input){

        foreach($input as $key => $val){
            if(strtotime($val) === false)
                return false;
        }

        return true;
    }

    public function beforeSave($options = array()){

        if(!parent::beforeSave($options))
            return false;

        //Convert datetime fields into appropriate database formats
        foreach(array('start','end') as $field){
            if(isset($this->data[$this->alias][$field])){
                $value = strtotime($this->data[$this->alias][$field]);
                if($value === false)
                    return false;
                else {
                    $this->data[$this->alias][$field] = date('Y-m-d H:i', $value);
                }
            }
        }
    }

    public function afterFind($results, $primary=false){

        $results = parent::afterFind($results, $primary);

        //Format the datetime fields
        foreach($results as $k => $v){
            foreach(array('start','end') as $field){
                if(isset($v[$this->alias][$field])){
                    $timestamp = strtotime($v[$this->alias][$field]);
                    $results[$k][$this->alias][$field] = date('n/j/Y g:ia', $timestamp);
                }
            }
        }

        return $results;
    }

    public function loungeUpdateCallback($action){

        $resource_url = false;
        $description = "";

        if($action == 'delete'){
            return false;
        }
        else {
            $entryId = $this->id;
            $entryTitle = isset($this->data[$this->alias]['title']) ?
                $this->data[$this->alias]['title'] :
                $this->field('title',array('CalendarEntry.id' => $entryId));

            $resource_url = "/calendar/view/$entryId";

            if($action == 'create'){
                $description = "created a new calendar entry <strong>$entryTitle</strong>.";
            }
            else {
                $description = "updated calendar entry <strong>$entryTitle</strong>.";
            }
        }

        return array($resource_url,$description);
    }

}

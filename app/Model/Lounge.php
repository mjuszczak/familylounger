<?php
class Lounge extends AppModel {

    public $name = 'Lounge';

    public $hasMany = array(
        'LoungeUser'
    );

    public $validate = array(
        'subdomain' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'An address is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The address cannot be empty'
            ),
            'valid' => array(
                'rule' => array('alphaNumeric'),
                'message' => 'The address is limited to numbers and letters'
            ),
            'minLen' => array(
                'rule' => array('minLength', 3),
                'message' => 'The address must be at least 3 characters long'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This address is already taken'
            ),
            'notReserved' => array(
                'rule' => array('notInList', array('www', 'api', 'blog')),
                'message' => 'This address is reserved'
            ),
        ),
        'name' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A lounge name is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'A lounge name is required'
            ),
        ),
        'privacy' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'You must specify a privacy preference'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'You must specify a privacy preference'
            ), 
            'valid' => array(
                'rule' => '/^public|private$/',
                'message' => 'Please select a valid privacy preference'
             ),
        )
    );

    public function isPublic($lounge_id){

        $lounge = $this->findById($lounge_id);
        $privacy = $lounge['Lounge']['privacy'];
        if($privacy == 'public')
            return true;
        else
            return false;
    }
}
?>

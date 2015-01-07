<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public $actsAs = array(
        'Containable',
    );

    /**
     * Validator: inverse of inList
     */
    public function notInList($input, $list){
        
        return !Validation::inList($input, $list);
    }

    /**
     * Validator: validate a user relationship
     *
     * To use this method you must call App::uses for LoungeUser
     */
    public function validUserRelationship($input){

        $suppliedRelationship = $input['user_relationship'];

        if(isset(LoungeUser::$relationships[$suppliedRelationship]))
            return true;

        return false;
    }

    /**
     * Validator: validate relationship (foreign_key) between this
     * model and belongsTo model
     */
    public function isValidForeignKey($data) {
        foreach ($data as $key => $value) {
            foreach ($this->belongsTo as $alias => $assoc) {
                if ($assoc['foreignKey'] == $key) {
                    $this->{$alias}->id = $value;
                    return $this->{$alias}->exists();
                }
            }
        }
        return false;
    }

    /**
     * Validator: validate multikey unique constraint
     */
    public function checkMultiKeyUniqueness($data, $fields){

        // check if the param contains multiple columns or a single one
        if (!is_array($fields))
            $fields = array($fields);

        // go through all columns and get their values from the parameters
        foreach($fields as $key)
            $unique[$key] = $this->data[$this->name][$key];

        // primary key value must be different from the posted value
        if (isset($this->data[$this->name][$this->primaryKey]))
            $unique[$this->primaryKey] = "<>" . $this->data[$this->name][$this->primaryKey];

        // use the model's isUnique function to check the unique rule
        return $this->isUnique($unique, false);
    }

    /**
     * Get validationErrors as a string
     *
     * @return string The validation errors data structure converted to a string
     */
    public function validationErrorsAsString($saveMany=false){

        if($saveMany){
            return print_r($this->validationErrors,true);
        }
        else {
            $message = "";
            foreach($this->validationErrors as $field => $fieldErrorMessages){
                array_walk_recursive($fieldErrorMessages,function(&$element,$index) use($field) {
                    $field = strtolower(Inflector::humanize($field));
                    $element = str_replace('%%f',$field,$element);
                    $element = str_replace('%f',$field,$element);
                    $element = ucfirst($element);
                });
                $message .= implode(". ",$fieldErrorMessages) . ". ";
            }
            return $message;
        }
    }

    /**
     * Return the last SQL statement executed
     */
    public function getSQLLog(){

        return $this->getDataSource()->getLog(false,false);
    }
}

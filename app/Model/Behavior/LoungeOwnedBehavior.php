<?php

/**
 * This behavior takes care of appending the lounge id
 * to all finds and saves. If the lounge id has already
 * been explicitly set it will not overwrite the lounge
 * id. This also makes it flexible enough that you 
 * shouldn't ever have to disable this behavior :)
 */

class LoungeOwnedBehavior extends ModelBehavior {

    public function setup($model, $settings) {

        if (!isset($this->settings[$model->alias])) {
                $this->settings[$model->alias] = array();
        }

        $this->settings[$model->alias] = array_merge(
            $this->settings[$model->alias],
            (array)$settings
        );
    }

    public function _getLoungeId(){

        $lounge = Configure::read('Lounge');
        return $lounge['id'];
    }

    public function beforeFind(Model $model, $query){

        if(!isset($query['conditions'])){

            $loungeId = $this->_getLoungeId();
            if(empty($loungeId)){
                throw new Exception('Couldn\'t get lounge id');
                return false;
            }

            $query['conditions'] = array(
                ($model->alias . '.lounge_id') => $loungeId
            );
        }
        else {
            $conditions = $query['conditions'];

            //Attempt to overwrite lounge_id if it has been supplied by the user
            if(isset($conditions['lounge_id']))
                return $query;
            elseif(isset($conditions["$model->alias.lounge_id"]))
                return $query;
            else {

                $loungeId = $this->_getLoungeId();
                if(empty($loungeId)){
                    throw new Exception('Couldn\'t get lounge id');
                    return false;
                }

                $query['conditions'] = array(
                    'AND' => array(
                        ($model->alias . '.lounge_id') => $loungeId,
                        $conditions
                    )
                );
            }
        }

        return $query;
    }

    public function beforeValidate(Model $model){

        $this->_addToWhitelist($model,'lounge_id');

        if($this->_setLoungeIdOnModel($model))
            return true;
        else {
            $model->invalidate('lounge_id','Unable to determine lounge id');
            return false;
        }
    }

    public function beforeSave(Model $model){

        if($this->_setLoungeIdOnModel($model))
            return true;
        else {
            $model->invalidate('lounge_id','Unable to determine lounge id');
            return false;
        }
    }

    /**
     * Set lounge id on a model
     */
    private function _setLoungeIdOnModel(&$model){

        if(isset($model->data['lounge_id']))
            return true;

        if(isset($model->data[$model->alias]['lounge_id']))
            return true;

        $loungeId = $this->_getLoungeId();
        if(empty($loungeId)){
            return false;
        }

        $model->data[$model->alias]['lounge_id'] = $loungeId;
        return true;
    }
}

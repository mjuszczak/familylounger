<?php

App::import("Model", "Update");

class LoungeUpdateBehavior extends ModelBehavior {

    public function setup($model, $settings) {

        if (!isset($this->settings[$model->alias])) {
                $this->settings[$model->alias] = array();
        }

        $this->settings[$model->alias] = array_merge(
            $this->settings[$model->alias],
            (array)$settings
        );
    }

    /*
     * Add associated lounge update record
     */
    public function afterSave($model, $created) {

        $update_record = array();

        $model_name = $model->alias;
        $model_controller = Inflector::underscore(Inflector::pluralize($model_name));
        $model_data = $model->data[$model->alias];
        $model_id = isset($model_data['id']) ? $model_data['id'] : $model->id;
        $lounge_id = isset($model_data['lounge_id']) ? $model_data['lounge_id'] :  $this->_getLoungeId();
        $user_id = isset($model_data['user_id']) ? $model_data['user_id'] : $this->_getUserId();
        $resource_url = false;
        $description = "";
        $action = ($created ? 'create' : 'update');

        if(method_exists($model,'loungeUpdateCallback')){
            $result = $model->loungeUpdateCallback($action,$model);
            if($result === false)
                return;
            list($resource_url,$description) = $result;
        }
        else {
            $resource_url = "/$model_controller/view/$model_id";
            if($action == 'create'){
                $description = "Created a new " . strtolower($model->alias) . ".";
            }
            else {
                $description = "Updated " . strtolower($model->alias) . ".";
            }
        }

        $update_record = array(
            'Update' => array(
                'lounge_id' => $lounge_id,
                'user_id' => $user_id,
                'model' => $model_name,
                'model_id' => $model_id,
                'resource_url' => $resource_url,
                'description' => $description,
                'action' => $action
            )
        );

        $updateModel = new Update();
        if(!$updateModel->save($update_record)){
            die($updateModel->validationErrorsAsString());
        }
    }

    /**
     * Remove any lounge updates
     */
    public function afterDelete($model){

        $modelData = $model->data;
        $modelId = false;
        if(isset($modelData[$model->alias]['id']))
            $modelId = $modelData[$model->alias]['id'];
        elseif(isset($modelData['id']))
            $modeId = $modelData['id'];
        else
            $modelId = $model->id;

        $updateModel = new Update();
        $updateModel->deleteAll(
            array(
                'Update.model' => $model->alias,
                'Update.model_id' => $modelId
            )
        );
    }

    public function _getLoungeId(){

        $lounge = Configure::read('Lounge');
        return $lounge['id'];
    }

    public function _getUserId(){
        $user = Configure::read('User');
        return $user['id'];
    }
}

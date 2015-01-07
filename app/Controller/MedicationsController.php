<?php

class MedicationsController extends AppController {

    public $components = array(
        'LoungeArea'
    );

   	public $helpers = array(
        'Html',
        'Form'
    );

    /**
     * Handles authorization for lounge specific actions
     */
    public function isLoungeAuthorized($loungePrivacy, $userLoungeRole){

        if($userLoungeRole == 'admin')
            return true;

        return false;
    }

    /**
     * Callback
     */
    public function beforeFilter(){

        parent::beforeFilter();

        $this->set('title_for_layout','My Meds');
    }

    /*
     * List of medications
     */
    public function index(){

        $medications = $this->Medication->find('all', array(
            'contain' => array(),
        ));

        $this->set('medications', $medications);
    }

    /*
     * Add new medication
     */
    public function add() {

        $this->set('add', true);

        if($this->request->is('post')){

            $result = $this->Medication->save($this->request->data);
            if($result){
                $this->_setFlash('Medication added successfully.', 'success');
                $this->redirect('/medications');
            }
            else {
                $valError = $this->Medication->validationErrorsAsString();
                $this->_setFlash("Unable to save this medication. ${valError}");
            }
        }

        $this->render('add-edit');
    }

    /*
     * Edit an existing post
     */
    public function edit($id=null) {

        $medication = $this->Medication->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Medication.id' => $id
            )
        ));

        if(empty($medication))
            return $this->redirect('/medications');

        if($this->request->is('post')){

            $this->Medication->id = $id;
            $result = $this->Medication->save($this->request->data);
            if($result){
                $this->_setFlash('Medication updated successfully.', 'success');
                $this->redirect('/medications');
            }
            else {
                $valError = $this->Medication->validationErrorsAsString();
                $this->_setFlash("Unable to update medication. ${valError}");
            }
        }
        else {
            $this->request->data = $medication;
        }

        $this->set('medId', $id);
        $this->set('add', false);
        $this->render('add-edit');
    }

    /*
     * Delete a medication
     */
    public function delete($id=null) {

         $medication = $this->Medication->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Medication.id' => $id
            )
        ));

        if(empty($medication))
            return $this->redirect('/medications');

        $result = $this->Medication->delete($id); 
        if($result){
            $this->_setFlash('Medication deleted successfully.', 'success');
            $this->redirect('/medications');
        }
        else {
            $this->_setFlash('Unabled to delete medication.', 'error');
            $this->redirect("/medications/edit/${id}");
        }
    }
}

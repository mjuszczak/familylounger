<?php

class TasksController extends AppController {

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

        $this->set('title_for_layout','To Do');
    }

    /*
     * List of tasks
     */
    public function index(){

        $this->paginate = array(
            'contain' => array(),
            'limit' => 7,
            'order' => array(
                'Task.created' => 'desc'
            )
        );

        $tasks = $this->paginate('Task');
        $this->set('tasks', $tasks);
    }

    /*
     * Toggle a task between incomplete and complete
     */
    public function toggle(){
        
        $taskId = $this->request->data('id');

        $task = $this->Task->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Task.id' => $taskId
            )
        ));

        if(empty($task))
            throw new NotFoundException('This task does not exist');

        $this->Task->id = $taskId;
        $result = $this->Task->save(array(
            'Task' => array(
                'completed' => ($task['Task']['completed'] ? '0' : '1')
            )
        ));

        if(!$result)
            throw new InternalErrorException('Failed to update task');

        $task = $this->Task->read(null, $taskId); 

        $this->set(array(
            'isCompleted' => $task['Task']['completed'],
            'completedOn' => date('M j, g:i a', strtotime($task['Task']['updated'])),
            '_serialize' => array(
                'isCompleted',
                'completedOn'
            )
        ));
    }

    /*
     * Add new Task
     */
    public function add() {

        $this->set('add', true);

        if($this->request->is('post')){

            $result = $this->Task->save($this->request->data);
            if($result){
                $this->_setFlash('Task added successfully.', 'success');
                $this->redirect('/tasks');
            }
            else {
                $valError = $this->Task->validationErrorsAsString();
                $this->_setFlash("Unable to save this task. ${valError}");
            }
        }

        $this->render('add-edit');
    }

    /*
     * Edit an existing post
     */
    public function edit($id=null) {

        $Task = $this->Task->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Task.id' => $id
            )
        ));

        if(empty($Task))
            return $this->redirect('/tasks');

        if($this->request->is('post')){

            $this->Task->id = $id;
            $result = $this->Task->save($this->request->data);
            if($result){
                $this->_setFlash('Task updated successfully.', 'success');
                $this->redirect('/tasks');
            }
            else {
                $valError = $this->Task->validationErrorsAsString();
                $this->_setFlash("Unable to update task. ${valError}");
            }
        }
        else {
            $this->request->data = $Task;
        }

        $this->set('medId', $id);
        $this->set('add', false);
        $this->render('add-edit');
    }

    /*
     * Delete a Task
     */
    public function delete($id=null) {

         $Task = $this->Task->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Task.id' => $id
            )
        ));

        if(empty($Task))
            return $this->redirect('/tasks');

        $result = $this->Task->delete($id); 
        if($result){
            $this->_setFlash('Task deleted successfully.', 'success');
            $this->redirect('/tasks');
        }
        else {
            $this->_setFlash('Unabled to delete Task.', 'error');
            $this->redirect("/tasks/edit/${id}");
        }
    }
}

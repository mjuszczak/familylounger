<?php

class Post extends AppModel {

    public $name = 'Post';

    public $actsAs = array(
	    'LoungeOwned',
        'LoungeUpdate'
    );

    public $hasMany = array(
        'Comment' => array(
            'dependent' => true
        ),
        'PostAttachment' => array(
            'dependent' => true
        )
    );

    public $belongsTo = array(
        'User',
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
        'contents' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Please add some post content'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please add some post content'
            ), 
        )
    );

    public function beforeSave($options){

        if(!parent::beforeSave($options))
            return false;

        //Format title
        if(isset($this->data[$this->alias]['title']))
            $this->data[$this->alias]['title'] = ucwords(strtolower($this->data[$this->alias]['title']));

	    //Strip HTML/Javascript from post data
        if(isset($this->data[$this->alias]['title']))
	        $this->data[$this->alias]['title'] = strip_tags($this->data[$this->alias]['title']);
        if(isset($this->data[$this->alias]['contents']))
	        $this->data[$this->alias]['contents'] = strip_tags($this->data[$this->alias]['contents']);

	    return true;
    }

    public function isOwnedBy($post_id, $user_id){

	    $post = $this->findById($post_id);
	    if($post[$this->alias]['user_id'] == $user_id)
		    return true;
	    else
		    return false;
    }

    public function loungeUpdateCallback($action){

        $resource_url = false;
        $description = "";

        if($action == 'delete'){
            return false;
        }
        else {
            $postId = $this->id;
            $postTitle = isset($this->data[$this->alias]['title']) ? 
                $this->data[$this->alias]['title'] : 
                $this->field('title',array('Post.id' => $postId));

            $resource_url = "/posts/view/$postId";

            if($action == 'create'){
                $description = "wrote a new blog post <strong>$postTitle</strong>.";
            }
            else {
                $description = "updated blog post <strong>$postTitle</strong>.";
            }
        }

        return array($resource_url,$description);
    }
}

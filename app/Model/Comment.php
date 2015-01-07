<?php
class Comment extends AppModel {

    public $name = 'Comment';

    public $actsAs = array(
        'LoungeOwned',
        'LoungeUpdate'
    );

    public $belongsTo = array(
        'User',
        'Post'
    );

    public $validate = array(
        'post_id' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Post ID is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Post ID cannot be empty'
            ),
            'isNumeric' => array(
                'rule' => 'numeric',
                'message' => 'Post ID must be an integer'
            ),
            'validForeignKey' => array(
                'rule' => array('isValidForeignKey'),
                'message' => 'Post does not exist'
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
        'contents' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'Please add some comment content'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please add some comment content'
            ),
        )
    ); 

    public function beforeSave($options){

        //Strip HTML/Javascript from comment data
        if(isset($this->data['Comment']['contents']))
            $this->data['Comment']['contents'] = strip_tags($this->data['Comment']['contents']);

        return true;
    }

    public function loungeUpdateCallback($action){

        $resource_url = false;
        $description = "";

        if($action == 'delete'){
            return false;
        }
        else {
            $comment_id = $this->id;
            $post_id = 0;
            $post_title = "Unknown";

            if(isset($this->data['Post']) &&
                isset($this->data['Post']['id']) &&
                isset($this->data['Post']['title'])){

                $post_id = $this->data['Post']['id'];
                $post_title = $this->data['Post']['title'];
            }
            else {
                $comment = $this->find('first',array(
                    'contain' => array(
                        'Post'
                    ),
                    'fields' => array(
                        'Post.title'
                    ),
                    'conditions' => array(
                        'Comment.id' => $comment_id
                    )
                ));
          
                $post_id = $comment['Post']['id']; 
                $post_title = $comment['Post']['title']; 
            }

            $resource_url = "/posts/view/$post_id#comment-$comment_id";

            if($action == 'create'){
                $description = "commented on post <strong>$post_title</strong>.";
            }
            else {
                $description = "updated a comment on post <strong>$post_title</strong>.";
            }
        }

        return array($resource_url,$description);
    }
}

<?php

class PostsController extends AppController {

    public $components = array(
        'LoungeArea'
    );

   	public $helpers = array(
        'Html', 'Form','Gravatar'
    );

    /**
     * Handles authorization for lounge specific actions
     */ 
    public function isLoungeAuthorized($loungePrivacy, $userLoungeRole){

        if($userLoungeRole == 'admin')
            return true;

        if($userLoungeRole == 'user'){
            return in_array(
                $this->action,
                array(
                    'index',
                    'view',
                    'attachment',
                    'addComment'
                )
            );
        }

        if($loungePrivacy == 'public'){
            return in_array(
                $this->action,
                array(
                    'index',
                    'view',
                    'attachment'
                )
            );
        }

        return false;
    }

    /**
     * Callback
     */
    public function beforeFilter(){

        parent::beforeFilter();

        $this->set('title_for_layout','Blog');
    }

    /*
    * List of most recent posts and comments
    */
    public function index(){

		//Retrieve most recent X posts
        $this->paginate = array(
            'contain' => array(
                'Comment',
                'User'
            ),
            'limit' => 4,
            'order' => array(
                'Post.updated' => 'desc'
            )
        );

		$posts = $this->paginate('Post');
		$this->set('posts',$posts);
    }

    /*
    * View post and associated comments
    */
    public function view($postId=null) {

        //Get post
	    $post = $this->Post->find('first',array(
            'contain' => array(
                'PostAttachment',
                'User'
            ),
            'conditions' => array(
                'Post.id' => $postId
            )
        ));

        if(empty($post))
            $this->redirect('/blog');

        //Get comments
        $comments = $this->Post->Comment->find('all', array(
            'contain' => array(
                'User'
            ),
            'conditions' => array(
                'Comment.post_id' => $postId
            )
        ));

        $this->set(array(
            'post' => $post,
            'comments' => $comments,
            'own_post' => $post['Post']['user_id'] == $this->Auth->User('id')
        ));
    }

    /*
     * Retrieve an attachment
     */
    public function attachment($attachmentId){

        $attachment = $this->Post->PostAttachment->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'PostAttachment.id' => $attachmentId
            )
        ));
        
        if(empty($attachment))
            throw new NotFoundException('Attachment not found');

        $filePath = $this->Post->PostAttachment->getAttachmentPath($attachment);
        $this->response->file($filePath);
        $this->response->type($attachment['PostAttachment']['content_type']);
        return $this->response;
    }

    /*
    * Add new post
    */
    public function add() {

        $this->set('add',true);

        if($this->request->is('post')) {

            //Append user_id to the post
            $user_id = $this->Auth->User('id');

            $post_data = array_merge_recursive(
                $this->request->data,
                array(
                    'Post' => array(
                        'user_id' => $user_id,
                    )
                )
            );

            $valid_field_list = array(
                'user_id',
                'title',
                'contents'
            );

            if($this->Post->save($post_data,true,$valid_field_list))
                $this->redirect("/posts/view/" . $this->Post->id);
            else
                $this->_setFlash($this->Post->validationErrorsAsString());
        }

        $this->render('add-edit');
    }

    /*
    * Edit an existing post
    */
    public function edit($id=null) {

        $post = $this->Post->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Post.id' => $id
            )
        ));

        if(empty($post))
            $this->redirect('/blog');

        $this->set('add',false);
        $this->set('postId',$id);

        if($this->request->is('post')){

            //User can only edit these fields
            $validFieldList = array('title','contents');	
    
            $this->Post->id = $id;
            if($this->Post->save($this->request->data,true,$validFieldList)){
                $this->redirect(array('action' => 'index'));
            }
            else {
                $this->_setFlash('Sorry but we were unable to update your post.');
            }
        }
        else {
            $this->request->data = $post;
        }

        $this->render('add-edit');
    }

    /*
    * Delete a post and its associated comments
    */
    public function delete($id=null) {

        $this->autoRender = false;

        $post = $this->Post->find('first',array(
            'contain' => array(),
            'conditions' => array(
                'id' => $id
            )
        ));

        if(empty($post))
            return $this->redirect('/blog');

        //We issue separate deletes as opposed to a single cascading
        //delete so that all of the callbacks are fired. This is
        //required to allow the LoungeUpdate behavior to cleanup
        //all Comment updates.
        $commentResult = $this->Post->Comment->deleteAll(
            array(
                'Comment.post_id' => $id
            ),
            true,
            true
        );

        $postResult = $this->Post->delete($id);
        
        if($commentResult && $postResult){
            $this->_setFlash('Post deleted','success');
            $this->redirect('/blog');
        }
        else {
            $this->_setFlash('We encountered an error while trying to delete this post');
            $this->redirect("/post/view/$id");
        }
    }

    /*
    * Add comment
    */
    public function addComment($postId=null) {

        $post = $this->Post->find('first',array(
            'contain' => array(),
            'conditions' => array(
                'Post.id' => $postId
            )
        ));

        if(empty($post))
            $this->redirect("/posts/view/$postId");

        if($this->request->is('post')){

            //Append user_id to the post
            $userId = $this->Auth->User('id');

            $comment = array_merge_recursive(
                $this->request->data,
                array(
                    'Comment' => array(
                        'post_id' => $postId,
                        'user_id' => $userId
                    )
                )
            );

            $validFieldList = array('post_id','user_id','contents');

            if(!$this->Post->Comment->save($comment,true,$validFieldList)){
                $this->_setFlash($this->Post->Comment->validationErrorsAsString());
            }
        }

        $this->redirect(array('action' => 'view',$postId));
    }
}

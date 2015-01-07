<?php

class PostAttachment extends AppModel {

    public $actsAs = array(
	    'LoungeOwned',
    );

    public $belongsTo = array(
       'Lounge',
       'Post'
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
        'file' => array(
            'requiredOnCreate' => array(
                'rule' => 'notEmpty',
                'on' => 'create',
                'required' => true,
                'message' => 'A file is required'
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'The file cannot be empty'
            ), 
        ),
    );

    public static function getAttachmentPath($attachment){

        $base = WWW_ROOT . "private/post.attachment.";

        if(is_array($attachment)) {
            return $base . $attachment['PostAttachment']['id'];
        }
        elseif(is_numeric($attachment)){ //$attachment = id
            return $base . $attachment;
        }

        throw \InvalidArgumentException('Unexpected attachment argument');
    }

    public function afterDelete(){

        $file = new File($this->getAttachmentPath($this->id));
        $file->delete();
    }
}

<?php

$this->extend('/Common/lounge_area');
$this->assign('title', ($add ? 'New Post' : 'Edit Post'));
?>

<div id="add-edit-post">
<?php
  echo $this->Form->create('Post',array(
    'action' => ($add ? 'add' : 'edit'),
    'inputDefaults' => array(
      'label' => false
    )
  ));
  echo $this->Form->input('title',array(
    'placeholder' => 'Title'
  ));
  echo $this->Form->input('contents');
  echo $this->Form->button('Save',array(
    'class' => 'button'
  ));
  echo $this->Form->button('Cancel',array(
    'class' => 'button cancel',
    'type' => 'button',
    'data-url' => '/blog'
  ));
  echo $this->Form->end();
?>
</div>

<?php

$this->extend('/Common/lounge_area');
$this->start('raw-title'); ?>
  <h2><?php echo ($add ? 'New Task' : 'Edit Task'); ?></h2>
<?php $this->end();
?>

<div id="add-edit-task">
<?php
  echo $this->Form->create('Task',array(
    'action' => ($add ? 'add' : 'edit'),
  ));
  echo $this->Form->input('description', array(
    'type' => 'text'
  ));
  echo $this->Form->button('Save',array(
    'class' => 'button'
  ));
  echo $this->Form->button('Cancel',array(
    'class' => 'button cancel',
    'type' => 'button',
    'data-url' => '/tasks'
  ));
  echo $this->Form->end();
?>
</div>

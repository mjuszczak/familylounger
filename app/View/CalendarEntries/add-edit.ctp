<?php

$this->extend('/Common/lounge_area');
$this->assign('title', ($add ? 'New Entry' : 'Edit Entry'));
?>

<div id="add-edit-calendar-entry">
  <?php echo $this->Form->create(); ?>
  <div class="col1">
    <?php
    echo $this->Form->input('title',array(
      'placeholder' => 'ex: regular check up'
    ));
    echo $this->Form->input('location');
    echo $this->Form->input('start', array(
      'type' => 'text',
      'class' => 'datetimepicker',
      'data-format' => 'm/d/Y g:ia',
      'data-formatTime' => 'g:ia',
      'data-step' => '30'
    ));
    echo $this->Form->input('end', array(
      'type' => 'text',
      'class' => 'datetimepicker',
      'data-format' => 'm/d/Y g:ia',
      'data-formatTime' => 'g:ia',
      'data-step' => '30'
    ));
    echo $this->Form->button('Save',array(
      'class' => 'button'
    ));
    echo $this->Form->button('Cancel',array(
      'class' => 'button cancel',
      'type' => 'button',
      'data-url' => '/calendar'
    ));
    ?>
  </div>
  <div class="col2">
    <?php
    echo $this->Form->input('notes', array(
      'type' => 'textarea',
      'class' => 'notes'
    ));
    ?>
  </div>
  <div class="clear"></div>
  <?php echo $this->Form->end(); ?>
</div>

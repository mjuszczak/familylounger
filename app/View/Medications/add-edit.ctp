<?php

$this->extend('/Common/lounge_area');

$this->start('raw-title'); ?>
  <h2><?php echo ($add ? 'New Medication' : 'Edit Medication'); ?></h2>
<?php $this->end();
?>

<div id="add-edit-medication">
<?php
  echo $this->Form->create('Medication',array(
    'action' => ($add ? 'add' : 'edit'),
  ));
  echo $this->Form->input('name',array(
    'placeholder' => 'ex: Advil'
  ));
  echo $this->Form->input('dosage', array(
    'placeholder' => '300mg'
  ));
  echo $this->Form->input('description', array(
    'type' => 'textarea',
  ));
  echo $this->Form->button('Save',array(
    'class' => 'button'
  ));
  echo $this->Form->button('Cancel',array(
    'class' => 'button cancel',
    'type' => 'button',
    'data-url' => '/medications'
  ));
  echo $this->Form->end();
?>
</div>

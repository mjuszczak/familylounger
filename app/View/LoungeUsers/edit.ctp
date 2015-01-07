<?php

$userId = $loungeUser['User']['id'];

$this->extend('/Common/lounge_area');
$this->start('raw-title'); ?>
  <h2><?php echo $loungeUser['User']['full_name']; ?></h2>
<?php $this->end();
?>

<div class="edit-lounge-user">
  <?php
  echo $this->Form->create('LoungeUser');
  echo $this->Form->input('user_role', array(
    'type' => 'select',
    'label' => 'Role',
    'options' => $roles
  ));
  echo $this->Form->input('user_relationship', array(
    'type' => 'select',
    'label' => 'Relationship',
    'options' => $relationships
  ));
  echo $this->Form->button('Save', array(
    'type' => 'submit',
    'class' => 'button'
  ));
  echo $this->Form->button('Cancel', array(
    'type' => 'button',
    'class' => 'button cancel',
    'data-url' => '/people'
  ));
  echo $this->Form->end();
  ?>
</div>

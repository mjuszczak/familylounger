<?php

$this->extend('/Common/lounge_area');
$this->assign('title', 'Invite a Friend or Family Member');
?>
<div id="add-person">
<?php
  echo $this->Form->create('LoungeInvitation');
  echo $this->Form->input('user_relationship', array(
    'type' => 'select',
    'options' => $relationshipOptions,
    'empty' => false,
    'default' => 'friend',
  ));
  echo $this->Form->input('first_name');
  echo $this->Form->input('email');
  echo $this->Form->input('message', array(
    'label' => 'Message (optional)',
    'placeholder' => 'Include a personal message...'
  ));
  echo $this->Form->button('Send',array(
    'class' => 'button'
  ));
  echo $this->Form->button('Cancel',array(
    'class' => 'button cancel',
    'type' => 'button',
    'data-url' => '/people'
  ));
  echo $this->Form->end();
?>
</div>

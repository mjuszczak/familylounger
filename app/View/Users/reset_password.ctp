<?php
$this->extend('/Common/generic');
$this->assign('title', 'Reset password');
?>
<div id="reset-password">
<?php
  echo $this->Form->create(null);
  echo $this->Form->input('password', array(
    'type' => 'password',
    'name' => 'password',
    'label' => 'Password'
  ));
  echo $this->Form->input('confirm_password', array(
    'type' => 'password',
    'name' => 'confirm_password'
  ));
  echo $this->Form->button('Submit', array(
    'class' => 'button'
  ));
  echo $this->Form->end();
?>
</div>

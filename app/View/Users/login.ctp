<?php
$this->extend('/Common/generic');
$this->assign('title', 'Login');
?>

<div id="login">
  <?php
    echo $this->Form->create('User');
    echo $this->Form->inout('redirect', array(
      'type' => 'hidden',
      'name' => 'data[redirect]'
    ));
    echo $this->Form->input('email', array(
      'type' => 'email'
    ));
    echo $this->Form->input('password', array(
      'type' => 'password'
    ));
    echo $this->Form->button('Login', array(
      'class' => 'button'
    ));
    echo $this->Form->end();
  ?>
</div>

<?php
$this->extend('/Common/generic');
$this->assign('title', 'Edit Profile');
?>
<div id="update-user">
   <section>
    <h2>Update Password</h2>
    <?php
      echo $this->Form->create('User');
      echo $this->Form->input('password');
      echo $this->Form->input('confirm_password', array(
        'type' => 'password',
        'name' => 'data[confirm_password]'
      ));
      echo $this->Form->button('Save', array(
        'type' => 'submit',
        'class' => 'button'
      ));
      echo $this->Form->button('Cancel', array(
        'type' => 'button',
        'class' => 'button cancel',
        'data-url' => '/users/profile'
      ));
      echo $this->Form->end();
    ?>
  </section>
  <section class="update-info">
    <h2>Update Info</h2>
    <?php
      echo $this->Form->create('User');
      echo $this->Form->input('first_name');
      echo $this->Form->input('last_name');
      echo $this->Form->input('email');
      echo $this->Form->input('bio');
      echo $this->Form->button('Save', array(
        'type' => 'submit',
        'class' => 'button'
      ));
      echo $this->Form->button('Cancel', array(
        'type' => 'button',
        'class' => 'button cancel',
        'data-url' => '/users/profile'
      ));
      echo $this->Form->end();
    ?>
  </section>
</div>

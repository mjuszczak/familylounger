<?php
  echo $this->Form->create('User',array(
    'url' => '/users/login',
    'method' => 'post',
    'class' => 'ajax',
  ));
?>
  <ul class="notices"></ul>
<?php
  echo $this->Form->hidden('redirect', array(
    'name' => 'redirect',
    'value' => '/mylounge'
  ));
  echo $this->Form->input('email', array(
    'type' => 'email',
    'id' => 'username',
    'class' => 'mediuminput',
    'required' => true
  ));
  echo $this->Form->input('password', array(
    'type' => 'password',
    'id' => 'password',
    'class' => 'mediuminput',
    'required' => true
  ));
  echo $this->Form->button('Login',array(
    'class' => 'button',
    'type' => 'submit'
  ));
  echo $this->Html->link(
    'Forgot Password?',
    '/forgot-password',
    array(
        'id' => 'forgot-password'
    )
  );
  echo $this->Form->end();
?>

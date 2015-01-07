<div style="padding-top:5px;">
<div class="float-left" style="margin-right:20px;">
  <a href="/">
    <img src="/img/miniwordmark.png" />
  </a>
</div>
<div class="float-right">
  <?php if($isLoggedIn) { ?>
    <a href="/myprofile">My Profile</a> |
    <a href="/logout">Logout</a>
  <?php } else {
    echo $this->Form->create('User', array(
      'action' => 'login',
      'class' => 'inline',
      'inputDefaults' => array(
        'label' => false,
        'div' => false
      )
    ));
    echo $this->Form->input('redirect', array(
      'type' => 'hidden',
      'name' => 'data[redirect]',
      'value' => Router::url($this->here, true)
    ));
    echo $this->Form->input('email', array(
      'type' => 'email',
      'placeholder' => 'email address'
    ));
    echo $this->Form->input('password', array(
      'type' => 'password',
      'placeholder' => 'password'
    ));
    echo $this->Form->button('Login', array(
      'class' => 'button small'
    ));
    echo $this->Form->end();
  } ?>
</div>
<div class="clear"></div>
</div>

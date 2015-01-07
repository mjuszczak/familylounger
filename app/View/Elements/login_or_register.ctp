<?php
  if(!isset($registerTitle))
    $registerTitle = 'Register';
  if(!isset($redirectUrl))
    $redirectUrl = $this->Html->url(null, true);
?>
<div class="login-register">
<div class="col col1">
  <h3>Login</h3>
  <form class="ajax" action="/users/login" method="post">
    <ul class="notices"></ul>
    <div class="input hidden">
      <input type="hidden" name="data[redirect]" value="<?php echo $redirectUrl; ?>" />
    </div>
    <div class="input text required">
      <label for="data[User][email]">Email</label>
      <input type="email" name="data[User][email]" required />
    </div>
    <div class="input text required">
      <label for="data[User][password]">Password</label>
      <input type="password" name="data[User][password]" required />
    </div>
    <button class="button" type="submit">Login</button>
    <a class="forgot-password" href="<?php echo HOME_URL; ?>/forgot-password">Forgot password?</a>
  </form>
</div>
<div class="col col2">
  <p>-- OR --</p>
</div>
<div class="col col3">
  <h3><?php echo $registerTitle; ?></h3>
  <form class="ajax reset-on-success" action="/register" method="post">
    <ul class="notices"></ul>
    <div class="input hidden">
      <input type="hidden" name="data[redirect]" value="<?php echo $this->Html->url( null, true ); ?>" />
    </div>
    <div class="input text required">
      <label for="data[User][first_name]">First Name</label>
      <input type="text" name="data[User][first_name]" required />
    </div>
    <div class="input text required">
      <label for="data[User][last_name]">Last Name</label>
      <input type="text" name="data[User][last_name]" required />
    </div>
    <div class="input text required">
      <label for="data[User][email]">Email</label>
      <input type="email" name="data[User][email]" required />
    </div>
    <div class="input password required">
      <label for="data[User][password]">Password</label>
      <input type="password" name="data[User][password]" required />
    </div> 
    <button class="button" type="submit">Register</button>
  </form>
</div>
<div class="clear"></div>
</div> <!-- /.login-register -->

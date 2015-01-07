<?php
$this->extend('/Common/generic');
$this->assign('title', 'Forgot Password?');
?>
<div id="forgot-password">
<p>Enter the email address associated with your account and we'll email you a password reset link.<p>
<form method="post">
  <div class="input email required">
    <input type="email" name="email" required />
  </div>
  <button class="button" type="submit">Submit</button>
</form>
</div>

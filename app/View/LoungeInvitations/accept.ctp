<?php
  $loungeName = $loungeInvitation['Lounge']['name'];
?>
<?php
  $this->extend('/Common/static_modal');
  $this->assign('title', 'Accept Invitation');
?>
<?php if($this->Session->read('Auth.User')) {
  $this->assign('instructions', "Would you like to accept this invitation to the <strong>$loungeName</strong> lounge?");
  ?>
  <form method="post">
    <button class="button" type="submit">Accept Invitation</button>
  </form>
<?php }
else { 
  $this->assign('instructions', 'You must login or register for a Familylounger account to accept this invitation.');  
  echo $this->element('login_or_register'); ?>
<?php } ?>

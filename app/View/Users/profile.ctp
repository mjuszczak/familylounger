<?php
  $userId = $user['User']['id'];
  $email = $user['User']['email'];
  $firstName = $user['User']['first_name'];
  $lastName = $user['User']['last_name'];
  $bio = $user['User']['bio'];
  $joined = $this->Time->format($user['User']['created'], '%m/%d/%y');
  $lastActive = 'unknown';
?>
<div id="user-profile">
  <section class="user">
    <div class="center">
      <?php echo $this->Gravatar->image($email); ?>
      <div class="info">
        <h2><?php echo $firstName . " " . $lastName; ?></h2>
        <?php if($isMyProfile) { ?>
          <a href="/users/update">Edit Profile</a>
          <br />
        <?php } ?>
        <span>Joined: <?php echo $joined; ?></span>
        <br />
        <span>Last active: <?php echo $lastActive; ?>
      </div>
      <div class="clear"></div>
    </div> <!-- /.center -->
  </section> <!-- /.user -->
  <section class="bio">
    <h2>Bio</h2>
    <?php if(empty($bio)) { ?>
      <p class="empty">No bio available</p>
    <?php } 
    else { ?>
      <p><?php echo $bio; ?></p>
    <?php } ?>
  </section>
</div>

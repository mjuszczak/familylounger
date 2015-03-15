<a id="miniwmark" href="/">
  <img src="/img/miniwordmark.png" />
</a>
<div id="urlmark"><?php echo $_SERVER['HTTP_HOST']; ?></div>
<div id="dispname">
  <?php if(!empty($lounge_name)) { ?>
    <?php echo $lounge_name; ?>
  <?php } ?>
</div>
<div id ="signout">
  <a href="/myprofile">My Profile</a> | <a href="/logout">Logout</a>
</div>

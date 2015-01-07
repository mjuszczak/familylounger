<?php echo $this->element('messages'); ?>
<div id="sectiontitle">
  <h2 class="float-left"><?php echo $this->fetch('title'); ?></h2>
  <div><?php echo $this->fetch('raw-title'); ?></div>
  <div class="clear"></div>
</div>
<div class="lounge-content <?php echo "user-role-$userRole"; ?>" >
  <?php echo $this->fetch('content'); ?>
</div>

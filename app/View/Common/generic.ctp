<div id="sectiontitle">
  <h2 class="float-left"><?php echo $this->fetch('title'); ?></h2>
  <div><?php echo $this->fetch('raw-title'); ?></div>
  <div class="clear"></div>
</div>
<?php echo $this->element('messages'); ?>
<?php echo $this->fetch('content'); ?>

<div id="static-modal">
  <div class="info">
    <h1><?php echo $this->fetch('title'); ?></h1>
    <p class="instructions"><?php echo $this->fetch('instructions'); ?></p>
  </div>
  <div class="img">
    <img src="/img/loungertopr.png" />
  </div>
  <div class="clear"></div>
  <div class="content">
    <?php echo $this->fetch('content'); ?>
  </div>
</div>

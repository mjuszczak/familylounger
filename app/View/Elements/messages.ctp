<ul id="messages">
  <?php echo $this->Session->flash('flash', array('element' => 'Messages/error')); ?>
  <?php echo $this->Session->flash('error', array('element' => 'Messages/error')); ?>
  <?php echo $this->Session->flash('success', array('element' => 'Messages/success')); ?>
</ul>

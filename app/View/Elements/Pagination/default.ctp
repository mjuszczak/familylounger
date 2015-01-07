<?php

    /**
     * Default pagination element
     */

    //Override the text displayed in previous and next buttons
    if(!isset($previousText))
        $previousText = 'Previous';
    if(!isset($nextText))
        $nextText = 'Next';
?>
<div id="pagination">
  <div class="float-left">
    <?php echo $this->Paginator->prev(
      '<< ' . __($previousText),
      array(
        'tag' => false,
        'class' => 'button'
      ),
      null,
      array('class' => 'button disabled'));
    ?>
  </div>
  <div class="float-right">
    <?php echo $this->Paginator->next(
      __($nextText) . ' >>',
      array(
        'tag' => false,
        'class' => 'button'
      ),
      null,
      array('class' => 'button disabled'));
    ?>
  </div>
  <div class="clear"></div>
</div>

<?php foreach($calendarItems as $hourIndex => $info ){
  $hour = $this->Time->format($info['meta']['starttime'], '%H');
?>
<div id="hour-<?php echo $hour; ?>" class="group hour">
  <div class="datebox">
    <?php echo $this->Time->format($info['meta']['starttime'], '%l:%M'); ?>
  </div>
  <div class="items">
    <ul>
      <?php foreach($info['items'] as $item){ ?>
        <li>
          <?php echo $this->element('../CalendarEntries/Elements/entry', array('entry' => $item)); ?>
        </li>
      <?php } ?>
    </ul>
  </div>
  <div class="full-date">
    <?php echo $this->Time->format($info['meta']['starttime'], '%l:%M %P'); ?>
  </div>
  <div class="clear"></div>
</div> 
<?php } ?>

<?php foreach($calendarItems as $dayIndex => $info ){ ?>
<div class="group day">
  <div class="datebox">
    <?php echo $this->Time->format($info['meta']['starttime'], '%a'); ?>
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
    <?php echo $this->Time->format($info['meta']['starttime'], '%b %e, %Y'); ?>
  </div>
  <div class="clear"></div>
</div> 
<?php } ?>

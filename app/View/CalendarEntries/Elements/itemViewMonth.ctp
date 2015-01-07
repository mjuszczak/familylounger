<?php foreach($calendarItems as $weekIndex => $info ){ ?>
<div class="group week">
  <div class="datebox">
    <?php
      $start = $this->Time->format($info['meta']['starttime'], '%b %e');
      $end = $this->Time->format($info['meta']['endtime'], '%b %e');
      echo str_replace(' ', '&nbsp;',"$start - $end");
    ?>
  </div>
  <div class="items">
    <ul>
      <?php foreach($info['items'] as $item){ ?>
        <li>
          <?php echo $this->element('../CalendarEntries/Elements/entryExtended', array('entry' => $item)); ?>
        </li>
      <?php } ?>
    </ul>
  </div>
  <div class="clear"></div>
</div> 
<?php } ?>

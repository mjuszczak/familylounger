<?php foreach($calendarItems as $monthIndex => $info ){ ?>
<div class="group month">
  <div class="datebox">
    <?php
      echo $this->Time->format($info['meta']['starttime'], '%B');
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
  <div class="full-date">
    <?php echo $this->Time->format($info['meta']['starttime'], '%B %Y'); ?>
  </div>
  <div class="clear"></div>
</div> 
<?php } ?>

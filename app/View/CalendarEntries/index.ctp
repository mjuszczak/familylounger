<?php

$this->extend('/Common/lounge_area');

$this->start('raw-title'); ?>
  <h2>Calendar</h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/calendar/add">&#x271A; New Entry</a>
  <?php } ?>
<?php $this->end();

$prevUrl = "/calendar/$view/$previousDatetime";
$nextUrl = "/calendar/$view/$nextDatetime";
if($view == 'day'){
  $prevUrl .= "#hour-08";
  $nextUrl .= "#hour-08";
}

?>

<div id="calendar">
  <div class="controls">
    <div class="browse">
      <div class="previous">
        <a href="<?php echo $prevUrl; ?>">
          <img src="/img/arrow_left.png" />
        </a>
      </div>
      <div class="current">
        <span><?php echo $viewDescription; ?></span>
      </div>
      <div class="next">
        <a href="<?php echo $nextUrl; ?>">
          <img src="/img/arrow_right.png" />
        </a>
      </div>
    </div>
    <div class="calendarview">
      <ul>
        <li><a href="/calendar/day/<?php echo time().'#hour-'.date('H'); ?>">Today</a></li>
        <li><a href="/calendar/day/<?php echo $currentDatetime; ?>#hour-08">Day</a></li>
        <li><a href="/calendar/week/<?php echo $currentDatetime; ?>">Week</a></li>
        <li><a href="/calendar/month/<?php echo $currentDatetime; ?>">Month</a></li>
        <li><a href="/calendar/list/<?php echo $currentDatetime; ?>">List</a></li>
      </ul>
    </div>
    <div class="clear"></div>
  </div> <!-- /controls -->
  <div id="calendaritemwrap">
    <?php
      echo $this->element('../CalendarEntries/Elements/itemView' . ucfirst($view));
    ?>
  </div>
</div>

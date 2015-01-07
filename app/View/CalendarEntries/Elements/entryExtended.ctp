<?php
  $id = $entry['CalendarEntry']['id'];
  $title = $entry['CalendarEntry']['title'];
  $location = $entry['CalendarEntry']['location'];
  $start = $entry['CalendarEntry']['start'];
  $end = $entry['CalendarEntry']['end'];
  $longerThanDay = (strtotime($end) - strtotime($start)) > (60*60*24);
  $startTimeFormat = "%b %e %l:%M%P";
  $endTimeFormat = $startTimeFormat;
  if(!$longerThanDay){
    $endTimeFormat = "%l:%M%P"; 
  }
  $timeDescr = trim($this->Time->format($start, $startTimeFormat)) . " - " .
    $this->Time->format($end, $endTimeFormat);
?>
<strong>
  <a href="/calendar/view/<?php echo $id; ?>">
    <?php echo $title; ?>
  </a>
</strong>
<?php if(!empty($location)) { echo "@$location "; } ?>
(<?php echo $timeDescr; ?>)

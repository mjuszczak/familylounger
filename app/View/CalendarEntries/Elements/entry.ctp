<?php
  $id = $entry['CalendarEntry']['id'];
  $title = $entry['CalendarEntry']['title'];
  $location = $entry['CalendarEntry']['location'];
  $start = $entry['CalendarEntry']['start'];
  $end = $entry['CalendarEntry']['end'];
  $longerThanDay = (strtotime($end) - strtotime($start)) > (60*60*24);
  $timeFormat = ($longerThanDay ? "%b %e - %l:%M%P" : "%l:%M%P");
  $timeDescr = trim($this->Time->format($start, $timeFormat)) . " - " .
    $this->Time->format($end, $timeFormat);
?>
<strong>
  <a href="/calendar/view/<?php echo $id; ?>">
    <?php echo $title; ?>
  </a>
</strong>
<?php if(!empty($location)) { echo "@$location "; } ?>
(<?php echo $timeDescr; ?>)

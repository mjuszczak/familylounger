<?php

$entryId = $entry['CalendarEntry']['id'];
$title = $entry['CalendarEntry']['title'];
$location = $entry['CalendarEntry']['location'];
$start = $entry['CalendarEntry']['start'];
$end = $entry['CalendarEntry']['end'];
$longerThanDay = (strtotime($end) - strtotime($start)) > (60*60*24);
$timesDescr = false;
if($longerThanDay){
    $timesDescr = $this->Time->format($start, '%c') . ' - ' . $this->Time->format($end, '%c');
}
else {
    $timesDescr = $this->Time->format($start, '%a %h %e %l:%M %P') . ' - ' . $this->Time->format($end, '%l:%M %P');
}
$notes = $entry['CalendarEntry']['notes'];
$createdBy = $entry['User']['full_name'];

$this->extend('/Common/lounge_area');
$this->start('raw-title'); ?>
  <h2><?php echo $title; ?></h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/calendar/edit/<?php echo $entryId; ?>">&#x270E; Edit</a> | 
    <?php
      echo $this->Html->link(
        '✗ Delete',
        "/calendar/delete/${entryId}",
        false,
        "Click OK to confirm you would like to delete this calendar entry."
      );
    ?>
  <?php } ?>
<?php $this->end();
?>

<div id="view-calendar-entry">
  <div class="header">
    <h3><?php echo $timesDescr; ?></h3>
  </div>
  <div>
    <dl>
      <dt>Location</dt>
      <dd><?php echo $location; ?></dd>
    </dl>
  </div>
  <div>
    <dl>
      <dt class="label">Created By</dt>
      <dd><?php echo $createdBy; ?></dd>
    </dl>
  </div>
  <div>
    <dl>
      <dt class="label">Notes</dt>
      <dd><?php echo $notes; ?></dd>
    </dl>
  </div>
</div>

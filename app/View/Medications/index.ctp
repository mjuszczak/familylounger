<?php

$this->extend('/Common/lounge_area');

$this->start('raw-title'); ?>
  <h2>Current Medications</h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/medications/add">&#x271A; New Medication</a>
  <?php } ?>
<?php $this->end();
?>

<div id="medications">
<?php if(!count($medications)){ ?>
  <div class="empty">
    <p>No medications added yet.</p>
  </div>
<?php } ?>
<?php foreach($medications as $med) {
  $id = $med['Medication']['id'];
  $name = $med['Medication']['name'];
  $dosage = $med['Medication']['dosage'];
  $description = $med['Medication']['description'];
  ?>
  <div class="medication">
    <div class="header">
      <strong><?php echo $name; ?></strong>
      <div class="float-right">
        <span class="dosage"><?php echo $dosage; ?></span>
      </div>
      <div class="clear"></div>
      <?php if(in_array($userRole, array('admin'))) { ?>
        <div class="actions">
          <a href="/medications/edit/<?php echo $id; ?>">Edit</a> |
          <?php echo $this->Html->link('Delete', "/medications/delete/${id}", false, 'Click OK to confirm you would like to delete this medication.'); ?>
        </div>
      <?php } ?>
    </div>
    <div class="description">
      <?php if(empty($description)) { ?>
        <div class="empty">No description provided</div>
      <?php } else { ?>
        <p><?php echo $description; ?></p>
      <?php } ?>
    </div>
  </div>
<?php } ?>
<div class="clear"></div>
</div> <!-- /medications -->

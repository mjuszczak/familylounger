<?php

$this->extend('/Common/lounge_area');

$this->assign('title', "Updates");
?>

<div id="updates">
  <?php if(!count($updates)) { ?>
    <div class="empty">
      <p>No updates available</p>
    </div>
  <?php } ?>
  <?php foreach($updates as $update){
    $user_fullname = $update['User']['full_name'];
    $update_type = Inflector::underscore($update['Update']['model']);
    $update_short_date = $this->Time->format(
      'M d',
       $update['Update']['created']);
    $update_long_date = $this->Time->format(
      'M, d, Y - h:iA',
      $update['Update']['created']);
    $description = $update['Update']['description'];
    $resource_url = $update['Update']['resource_url'];
    ?>
    <div class="updatesummary">
      <div class="datebox"><?php echo $update_short_date; ?></div>
      <div class="title <?php echo $update_type; ?>">
        <?php echo $user_fullname . " - " . $description; ?>
        <a href="<?php echo $resource_url; ?>">Go ></a>
      </div>
      <div class="datetime"><?php echo $update_long_date; ?></div>
    </div>
  <?php } ?>
  <?php if($this->Paginator->counter('{:pages}') > 1) {
    echo $this->element('Pagination/default');
  } ?>
</div>

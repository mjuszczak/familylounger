<?php

$this->extend('/Common/lounge_area');
$this->start('raw-title'); ?>
  <h2>Pending Requests</h2>
  &#x2015;
  <a href="/people/invite">&#x271A; Invite Friend</a> 
<?php
$this->end();
?>
<div id="pending-requests">
<?php if(!count($requests)) { ?>
  <div class="empty">No requests pending</div>
<?php } ?>
<ul>
  <?php foreach($requests as $request) { ?>
    <li>
      <div class="requester">
        <div class="image">
          <?php echo $this->Gravatar->image($request['User']['email'],array('s' => 60)); ?>
        </div>
        <div class="info">
          <?php echo $request['User']['full_name']; ?>
          <br />
          <?php echo $this->Time->niceShort($request['LoungeRequest']['created']); ?>
          <br />
          <a class="message" data-title="Message" data-msg="<?php echo $request['LoungeRequest']['message']; ?>">View message</a>
        </div>
      </div>
      <div class="actions">
        <a href="/people/requests/accept/<?php echo $request['LoungeRequest']['id']; ?>">Accept</a> |
        <a href="/people/requests/ignore/<?php echo $request['LoungeRequest']['id']; ?>">Ignore</a>
      </div>
      <div class="clear"></div>
    </li>
  <?php } ?>
</ul>
<?php if($this->Paginator->counter('{:pages}') > 1) {
  echo $this->element('Pagination/default');
} ?>
</div> <!-- /#pending-requests -->

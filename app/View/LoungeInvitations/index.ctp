<?php

$this->extend('/Common/lounge_area');
$this->start('raw-title'); ?>
  <h2>Pending Invitations</h2>
  &#x2015;
  <a href="/people/invite">&#x271A; Invite Friend</a>
<?php
$this->end();
?>
<div id="pending-invitations">
<?php if(!count($invitations)) { ?>
  <div class="empty">No invitations pending</div>
<?php } ?>
<ul>
  <?php foreach($invitations as $invite) {
    $inviteId = $invite['LoungeInvitation']['id'];
    ?>
    <li>
      <div class="invitee">
        <div class="image">
          <?php echo $this->Gravatar->image($invite['LoungeInvitation']['email'],array('s' => 60)); ?>
        </div>
        <div class="info">
          <?php echo $invite['LoungeInvitation']['first_name']; ?>
          <br />
          <?php echo $invite['LoungeInvitation']['user_relationship']; ?>
          <br />
          <?php echo $this->Time->niceShort($invite['LoungeInvitation']['created']); ?>
        </div>
      </div>
      <div class="actions">
        <?php
          echo $this->Html->link(
            'Delete',
            "/people/invites/delete/${inviteId}",
            false, 
            "Click OK to confirm you would like to delete this invitation."
          );
        ?>
      </div>
      <div class="clear"></div>
    </li>
  <?php } ?>
</ul>
<?php if($this->Paginator->counter('{:pages}') > 1) {
  echo $this->element('Pagination/default');
} ?>
</div> <!-- #pending-invitations -->

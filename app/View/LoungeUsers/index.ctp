<?php

$this->extend('/Common/lounge_area');

$this->start('raw-title'); ?>
  <h2>People</h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/people/invite">&#x271A; Invite Friend</a>
    <nav style="float:right;">
      <a href="/people/invites">Pending Invitations (<?php echo $inviteCount; ?>)</a> |
      <a href="/people/requests">Pending Requests (<?php echo $requestCount; ?>)</a>
    </nav>
  <?php } ?>
<?php $this->end();
?>

<div id="people">
<?php if(!count($loungeUsers)){ ?>
  <div class="empty">
    <p>No one has joined your lounge yet</p>
  </div>
<?php } ?>
<?php foreach($loungeUsers as $loungeUser) {
    $role = $loungeUser['LoungeUser']['user_role'];
    $relation = $loungeUser['LoungeUser']['user_relationship'];
    $user = $loungeUser['User'];
    $userId = $user['id'];
    $email = $user['email'];
    $fullName = $user['full_name'];
    $bio = $user['bio'];
  ?>
  <div class="person">
    <div class="name">
      <?php echo $fullName; ?>
    </div>
    <div class="relation-role">
      <?php echo ucfirst($relation); ?>
      <?php if(in_array($userRole, array('admin'))) { ?>
        <?php echo "/" . ucfirst($role); ?>
        <?php
        $loggedInUserId = $this->Session->read('Auth.User.id');
        if($loggedInUserId != $userId) { ?>
          <div class="actions">
            <a class="edit" href="/people/edit/<?php echo $userId; ?>">Edit</a> |
            <?php
              echo $this->Html->link(
                'Unfriend',
                "/people/delete/${userId}",
                false,
                "Click OK to confirm you would like to unfriend this person."
              );
            ?>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
    <div class="separator"></div>
    <div class="image">
      <?php echo $this->Gravatar->image($email); ?>
    </div>
    <div class="bio">
      <?php if(empty($bio)) { ?>
        <div class="empty">No bio supplied</div>
      <?php }
      else { ?>
        <p><?php echo $bio; ?></p>
      <?php } ?>
    </div>
  </div> <!-- /#person -->
<?php } ?>
<div class="clear"></div>
<?php if($this->Paginator->counter('{:pages}') > 1) {
  echo $this->element('Pagination/default');
} ?>
</div><!-- /#people -->

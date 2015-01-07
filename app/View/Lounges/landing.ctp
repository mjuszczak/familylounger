<?php
  $this->extend('/Common/static_modal');
  $this->assign('title', 'Private Lounge');
?>
<div id="lounge-landing">
<?php if($this->Session->read('Auth.User')) {
  $this->assign('instructions', 'This lounge is accessible to lounge members only. Use the form below to request access to the lounge.');
  ?>
  <form class="ajax reset-on-sucess send-invite" action="/request-invite">
    <ul class="notices"></ul>
    <button class="button" type="submit">Send Invitation Request</button>
    <div class="input text">
      <label for="data[LoungeRequest][message]">Message (optional)</label>
      <textarea name="data[LoungeRequest][message]" placeholder="Include a personal message..."></textarea>
    </div>
  </form>
<?php }
else {
  $this->assign('instructions', 'This lounge is accessible to lounge members only. Please login or register for a Familylounger account.');
  echo $this->element('login_or_register', array(
    'redirectUrl' => $this->Html->url('/', true)
  ));
} ?>
<a href="<?php echo HOME_URL; ?>">&#171; Return to the home page</a>
</div>

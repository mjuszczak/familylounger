Hi <?php echo $inviteeFirstName ?>,

You've been invited to the <?php echo $loungeName; ?> lounge. To accept this invitation, visit the link below.

<?php echo $acceptLink; ?>

<?php if(!empty($message)) { ?>

Your invitation included a personal message from the owner:

<?php echo $message; ?>

<?php } ?>

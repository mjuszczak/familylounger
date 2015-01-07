<div class="col col1">
  <?php
    echo $this->Form->create(false, array(
      'url' => '/create',
      'method' => 'post',
      'class' => 'ajax'
    ));
  ?>
  <ul class="notices"></ul>
  <?php
    echo $this->Form->input('name', array(
      'name' => 'name',
      'label' => 'Lounge Name',
      'required' => true,
      'placeholder' => 'ex: JustLive'
    ));
    echo $this->Form->input('address', array(
      'name' => 'address',
      'label' => 'Lounge Address',
      'required' => true,
      'style' => 'width: 50%;',
      'placeholder' => 'ex: justlive',
      'after' => '<span>.' . SITE_DOMAIN . '</span>'
    ));
    echo $this->Form->input('relationship', array(
      'name' => 'relationship',
      'label' => 'I am a',
      'options' => $relationships,
      'required' => true
    ));
    echo $this->Form->input('firstname', array(
      'label' => 'First Name',
      'required' => true
    ));
    echo $this->Form->input('lastname', array(
      'label' => 'Last Name',
      'required' => true
    ));
    echo $this->Form->input('email', array(
      'type' => 'email',
      'required' => true
    ));
    echo $this->Form->input('password', array(
      'type' => 'password',
      'required' => true
    ));
    echo $this->Form->button('Create Lounge', array(
      'type' => 'submit',
      'class' => 'button'
    ));
    echo $this->Form->end();
  ?>
</div> <!-- /.col1 -->
<div class="col col2">
  <div class="disclaimer">
	<strong>Disclaimer:</strong><br>
    FamilyLounger.com and the owner(s) of the domain operate the website as a non-commercial public service. Each "lounge" is private and participation is limited by invitation only. FamilyLounger.com accepts no liability for comments that are posted to this site. If you have a concern about postings, please notify us at <a href="mailto:group@bitlancer.com">group@bitlancer.com</a>.
  </div>
</div> <!-- /.col2 -->
<div class="clear"></div>

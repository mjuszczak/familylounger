<?php
  echo $this->Form->create(null, array(
    'url' => '/find',
    'method' => 'post',
    'id' => 'search',
    'inputDefaults' => array(
      'div' => false,
      'label' => false
    )
  ));
  echo $this->Form->input('search', array(
    'name' => 'search',
    'inputDiv' => false,
    'placeholder' => 'ex: justin',
    'pattern' => '.{2,}',
    'title' => 'At least 2 characters are required',
    'required' => true,
    'autocomplete' => 'off'
  ));
  echo $this->Form->button('Search', array(
    'type' => 'submit',
    'class' => 'button'
  ));
  echo $this->Form->end();
?>
<div>
  <ul id="search-results"></ul>
  <div class="clear"></div>
</div>

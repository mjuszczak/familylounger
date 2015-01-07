<?php $this->Html->script('home', array('inline' => false)); ?>
<div id="homesignup">
  <div id="loginwrap">
    <div class="mainlogin">
	  <a href="#" class="btn-slide">
        <img src="/img/createlounge_norm.png" alt="Create Your Lounge" width="289" height="75" class="img-swap" />
      </a>
	</div>
	<div class="easyprivate">
	  ( Signing up is easy, private, &amp; free )<br />
	  <div id="signin">
	    <a href="#" class="login">Existing users, click here to log in</a>
	  </div>
	</div>
	<div id="searcharea">
	  <br />
	  <a href="#" class="homesearch">Search for existing lounges</a>
	</div>
  </div> <!-- /#loginwrap -->
</div><!--/#homesignup -->

<div class="signinform">
  <div class="formwrap">
    <?php echo $this->element('../Pages/Elements/home/form.login'); ?>
  </div>
  <div class="closedrops">
	<a href="#">Close Panel</a>
  </div>
  <div class="clear"></div>
</div><!--/#signinform -->

<div class="signup clear">
  <div class="formwrap">
    <?php echo $this->element('../Pages/Elements/home/form.create_lounge'); ?>
  </div>
  <div class="closedrops">
	<a href="#">Close Panel</a>
  </div>
  <div class="clear"></div>
</div><!-- /.signup -->

<div class="searchlounges">
  <div class="formwrap">
	<?php echo $this->element('../Pages/Elements/home/form.search_for_lounge'); ?>	
  </div>
  <div class="closedrops">
	<a href="#">Close Panel</a>
  </div>
  <div class="clear"></div>
</div><!-- /.searchlounges -->

<div id="homeinfo">
  <div class="infobox">
	<div class="imgbox">
      <img src="/img/multicon_home.png" width="118" height="114" alt="" />
	</div>

	<p><strong>For Patients and Loved Ones</strong></p>

	<p>FamilyLounger helps patients and loved ones stick together, stay
	positive and make the most of the healing process. You get easy-to-use
	tools including a blog to keep loved ones updated, a contact book to keep
	everybody connected, a calendar to keep track of dates, and more.<br />
  </div> <!-- /.infobox -->

  <div class="infobox topline">
	<div class="imgbox">
      <img src="/img/multicon_lounger.png" width="118" height="114" alt="" />
    </div>

	<p><strong>Did you know?</strong></p>

	<p>FamilyLounger was created by a cancer patient during his treatment. it
	is dedicated to all the caring people who supported him through his
	journey.<br />
	<a href="/pages/justins_story" class="teal">read his story &gt;</a><a href="#"></a></p>
  </div>

  <!-- ie7 hack -->
  <div class="showtoie7">
	<div class="infobox">
	  <div class="imgbox"></div>
	</div>
  </div>
</div><!-- /#homeinfo -->

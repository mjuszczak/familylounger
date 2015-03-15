<?php
  $c = $this->params['controller'];

  $updatesControllers = array('Updates');
  $blogControllers = array('Posts','Comments');
  $calendarsControllers = array('CalendarEntries');
  $peopleControllers = array('LoungeUsers','LoungeInvitations','LoungeRequests');
  $medsControllers = array('Medications');
  $tasksControllers = array('Tasks');

?>
<nav id="loungenav">
  <div class="navicon blog <?php if(in_array($c,$blogControllers)) { echo 'active pop'; } ?>" >
    <a href="/blog"></a>
  </div>
  <div class="navicon calendar <?php if(in_array($c,$calendarsControllers)) { echo 'active pop'; } ?>" >
    <a href="/calendar"></a>
  </div>
  <div id="minilogo"></div>
</nav>

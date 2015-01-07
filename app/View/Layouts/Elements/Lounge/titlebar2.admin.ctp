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
  <div class="navicon updates <?php if(in_array($c,$updatesControllers)) { echo 'active pop'; } ?>" >
    <a href="/updates"></a>
  </div>
  <div class="navicon blog <?php if(in_array($c,$blogControllers)) { echo 'active pop'; } ?>" >
    <a href="/blog"></a>
  </div>
  <div class="navicon calendar <?php if(in_array($c,$calendarsControllers)) { echo 'active pop'; } ?>" >
    <a href="/calendar"></a>
  </div>
  <div class="navicon people <?php if(in_array($c,$peopleControllers)) { echo 'active pop'; } ?>" >
    <a href="/people"></a>
  </div>
  <div class="navicon meds <?php if(in_array($c,$medsControllers)) { echo 'active pop'; } ?>" >
    <a href="/medications"></a>
  </div>
  <div class="navicon tasks <?php if(in_array($c,$tasksControllers)) { echo 'active pop'; } ?>" >
    <a href="/tasks"></a>
  </div>
  <div id="minilogo"></div>
</nav>

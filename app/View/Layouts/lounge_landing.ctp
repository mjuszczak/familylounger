<!DOCTYPE html>
<head>
  <?php echo $this->element('../Layouts/Elements/head'); ?>
</head>
<body class="lounge lounge-landing">
<div id="pagewrapper">
  <header id="header">
    <div class="contentwrapper">
      <div id="titlebar1">
        <?php echo $this->element("../Layouts/Elements/Lounge/titlebar1.anon"); ?>
      </div>
    </div>
  </header>
  <section id="content">
    <div class="contentwrapper">
    </div>
  </section>
</div> <!-- /#pagewrapper -->
<div id="footerwrapper">
  <footer id="loungefooter">
    <div class="logo">
      <img src="/img/footer_logo.png" width="234" height="48" alt="">
      <br>
      <span class="push">Create a Lounge, it's fast and simple.</span>
    </div>
    <div class="clear"></div>
  </footer>
</div> <!-- /#footerwrap -->
<div class="overlay">
  <div class="real-content">
    <?php echo $this->fetch('content'); ?>
  </div>
</div>
<?php echo $this->element('../Layouts/Elements/scripts'); ?>
</body>
</html>

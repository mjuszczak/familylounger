<!DOCTYPE html>
<head>
  <?php echo $this->element('../Layouts/Elements/head'); ?>
  <meta http-equiv="refresh" content="300">
</head>
<body class="lounge">
<div id="pagewrapper">
  <header id="header">
    <div class="contentwrapper">
      <div id="titlebar1">
        <?php echo $this->element("../Layouts/Elements/Lounge/titlebar1.$userLoungeRole"); ?>
      </div>
      <div id="titlebar2">
        <?php echo $this->element("../Layouts/Elements/Lounge/titlebar2.$userLoungeRole"); ?>
      </div>
    </div>
  </header>
  <section id="content">
    <div class="contentwrapper">
      <?php echo $this->fetch('content'); ?>
    </div>
  </section>
</div> <!-- /#pagewrapper -->
<div id="footerwrapper">
  <footer id="loungefooter">
    <div class="logo">
      <a href="/">
        <img src="/img/footer_logo.png" width="234" height="48" alt="">
        <br>
        <span class="push">Create a Lounge, it's fast and simple.</span>
      </a>
    </div>
    <?php if($userLoungeRole == 'admin')
      echo $this->element('../Layouts/Elements/Lounge/footer_extras.admin');
    ?>
    <div class="clear"></div>
  </footer>
</div> <!-- /#footerwrap -->
<?php echo $this->element('../Layouts/Elements/scripts'); ?>
</body>
</html>

<!DOCTYPE html>
<head>
  <?php echo $this->element('../Layouts/Elements/head'); ?>
</head>
<body class="default">
<div id="pagewrapper">
  <header id="header">
    <a href="/">
      <img style="display:block; margin:auto;" src="/img/homelogo.png" />
    </a>
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
    <div class="clear"></div>
  </footer>
</div> <!-- /#footerwrap -->
<?php echo $this->element('../Layouts/Elements/scripts'); ?>
</body>
</html>

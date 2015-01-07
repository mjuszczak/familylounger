<!DOCTYPE html>
<head>
  <?php echo $this->element('../Layouts/Elements/head'); ?>
</head>
<body class="home">
<div id="pagewrapper">
    <div id="pinkline"></div>
    <div id="homelogo">
        <div class="logo">
            <a href="/" alt="home">
                <img src="/img/homelogo.png" width="809" height="235" alt="FamilyLounger">
            </a>
        </div>
    </div>
    <?php echo $this->fetch('content'); ?>
    <div id="push"></div>
</div>
<div id="footerwrapper">
<footer id="loungefooter">
<div class="contentwrap" style="margin-left:auto; margin-right:auto;">
    <a href="/">
      <div class="logo">
          <img src="/img/footer_logo.png" width="234" height="48" alt=""><br>
          <span class="push">Create a Lounge, it's fast and simple.</span>
      </div>
    </a>
    <div class="homecopyright">&copy; FamilyLounger.com created by Justin Yuen</div>
    <div class="clear"></div>
</div>
</footer>
</div>
<?php echo $this->element('../Layouts/Elements/scripts'); ?>
</body>
</html>

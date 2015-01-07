<?php echo $this->Html->charset(); ?>
<title><?php echo $title_for_layout; ?> - Familylounger</title>
<?php echo $this->fetch('meta'); ?>
<?php echo $this->Html->meta('icon'); ?>

<link href='http://fonts.googleapis.com/css?family=Lato:400,400italic,900,900italic' rel='stylesheet' type='text/css' />
<link type="text/css" rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<link type="text/css" rel="stylesheet" href="/jquery/plugins/datetimepicker/jquery.datetimepicker.css" />
<link type="text/css" rel="stylesheet" href="/jquery/plugins/lightbox/css/lightbox.css" />
<link type="text/css" rel="stylesheet" href="/css/app.css" />
<!--[if IE 7]>
    <link href="/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<?php echo $this->fetch('css'); ?>

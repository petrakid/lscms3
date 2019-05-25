<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="msapplication-tap-highlight" content="no" />
  <meta name="robots" content="noindex, nofollow" />
  <title><?php if(isset($a)) { echo $a->getPageTitle($_GET['s']); } else { echo 'Log In'; } ?></title>
  
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  
  <link rel="stylesheet" href="<?php echo $g['site_url'] ?>/css/materialize.css" />
  <link rel="stylesheet" href="<?php echo $g['site_url'] ?>/css/a-style.css" media="screen,projection" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous" />
</head>

<body>
<div id="loader-wrapper">
<div id="loader"></div>
<div class="loader-section section-left"></div>
<div class="loader-section section-right"></div>
</div>

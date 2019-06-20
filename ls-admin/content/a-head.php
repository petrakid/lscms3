<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="msapplication-tap-highlight" content="no" />
  <meta name="robots" content="noindex, nofollow" />
  <title><?php if(isset($a)) { echo $a->getPageTitle($_GET['s']); } else { echo 'Log In'; } ?></title>
  
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

     <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-ext-addclass.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-image-attributes.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-image-shapes.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-image-depths.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-list-styles.js"></script>                     
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-add-text-tags.js"></script> 
	<script>
     UPLOADCARE_PUBLIC_KEY = '<?php echo $g['uploadcare_api'] ?>';
	var uckey = '<?php echo $g['uploadcare_api'] ?>';		
     </script>
  
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

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $p->getPageTitle($_GET['p']) ?></title>

<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" />
<link rel="stylesheet" href="<?php echo $g['site_url'] ?>/css/style.css" media="screen,projection" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.css" />

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/moment/main.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.js"></script>

<script>
WebFont.load({
     google: {
          families: ['<?php echo $st->getWebFonts('parent_menu_font') ?>', '<?php echo $st->getWebFonts('child_menu_font'); ?>', '<?php echo $st->getWebFonts('title_font') ?>', 'Material+Icons']
     }
});
</script>

<?php
if(isset($_SESSION['isLoggedIn'])) {
     ?>
     <link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet" />  
     <script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
     
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/elfinder/js/elfinder.min.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/elfinder/js/extras/editors.default.min.js"></script>     
     
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-ext-addclass.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-image-attributes.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-image-shapes.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-image-depths.js"></script>                
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-list-styles.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-add-text-tags.js"></script>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/sup/summernote-ext-filemanager.js"></script>                 
	<script>
     UPLOADCARE_PUBLIC_KEY = '<?php echo $g['uploadcare_api'] ?>';
	var uckey = '<?php echo $g['uploadcare_api'] ?>';		
     </script>
	
     <?php
}
if(!isset($_SESSION['isLoggedIn'])) {
     ?>

<script type="text/javascript">
  var _paq = window._paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="https://analytics.luthersites.net/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '<?php echo $g['mamoto_id'] ?>']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
     
     <?php
}
?>



</head>


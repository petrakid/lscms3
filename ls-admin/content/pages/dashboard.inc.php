<?php
if(isset($_SESSION['isLoggedIn'])) {
     ?>

<iframe id="autopanel" src="https://analytics.luthersites.net/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Dashboard&actionToWidgetize=index&idSite=<?php echo $g['mamoto_id'] ?>&period=week&date=yesterday" frameborder="0" marginheight="0" marginwidth="0"  width="100%" height="100%" seamless="true"></iframe>
<style>
#autopanel {
     min-height: 1000px;
}
</style>
     <?php
} else {
     die('You are not authorized!');
}
?>


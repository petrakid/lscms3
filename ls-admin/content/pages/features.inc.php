<div class="row">
<h2 class="header">Site Features</h2>
  <?php
  $r = 1;
  $feats = $plg->getPlugins();
  while($ft = $feats->fetch(PDO::FETCH_ASSOC)) {
     if($r >= 4) {
          echo '</div><div class="row">';
          $r = 1;
     }
     ?>
  
     <div class="col s12 m6 l4">
     <div class="card horizontal">
     <div class="card-image">
     <img src="<?php echo $g['site_url'] ?>/ls-admin/content/assets/img/<?php echo $ft['plugin_image'] ?>" />
     </div>
     <div class="card-stacked">
     <div class="card-content">
     <p><?php echo stripslashes($ft['plugin_text']) ?></p>
     </div>
     <div class="card-action">
     <a href="<?php echo $g['site_url'] ?>/admin/<?php echo $ft['plugin_link'] ?>/"><?php echo stripslashes($ft['plugin_link_text']) ?></a>
     </div>
     </div>
     </div>
     </div>
     
     <?php
     $r++;
}
?>

</div>

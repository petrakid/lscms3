     
     <main>
     
     <?php
     $fullwidth = 0;
     $page = $p->getContent($_GET['p'], 1);
     if(isset($_GET['quickedit']) && isset($_SESSION['isLoggedIn'])) {
          if($page->rowCount() == 0) {
               $pg = $page->fetch(PDO::FETCH_ASSOC);
               echo '<div id="edit_new"></div>';
               ?>
                            
               <?php
          }
     }
     if($page != '') {
          $pg = $page->fetch(PDO::FETCH_ASSOC);
          if(isset($_GET['quickedit']) && isset($_SESSION['isLoggedIn'])) {
               echo '<div class="section"><div class="row"><div class="col s12 m12 l12"><div id="summernote">'. $pg['section_content'] .'</div></div></div></div>';
          } else {               
               if($pg['show_carousel'] == 1) {
                    $car = new Carousel($db);
                    $car_settings = $car->carouselSettings();
                    $cs = $car_settings->fetch(PDO::FETCH_ASSOC);
                    $fullwidth = $cs['c_fullWidth'];
                    if($cs['c_fullWidth'] == 1) {
                         $full = 'fullscreen';
                    } else {
                         $full = '';
                    }
                    echo '<div class="section"><div class="row"><div class="col m12 l12">';
                    echo '<div class="slider '. $full .'"><ul class="slides">';
                    $slides = $car->carouselSlides(0);
                    $num = 1;
                    while($sl = $slides->fetch(PDO::FETCH_ASSOC)) {
                         echo '<li class="slide">';
                         if($sl['cs_type'] == 2) {
                              ?>
                              <div class="carousel-fixed-item center">
                              <!-- future enhancement-->
                              </div>
                              <?php
                         } else {
                              if($sl['cs_link'] > '') {
                                   ?>
                                   <a class="" href="<?php echo $sl['cs_link'] ?>" target="<?php echo $sl['cs_target'] ?>">
                                   <img src="<?php echo $g['site_url'] ?>/content/assets/carousel/<?php echo $sl['cs_image'] ?>" style="width: 100%;" />
                                   </a>
                                   
                                   <?php
                              } else {
                                   ?>
                                   <img src="<?php echo $g['site_url'] ?>/content/assets/carousel/<?php echo $sl['cs_image'] ?>" style="width: 100%;" />
                                   
                                   <?php
                              }
                         }
                         echo '</li>';
                         $num++;
                    }
                    echo '</ul></div></div></div></div>';
                    ?>
                    
                    <script>
                    $(function() {
                         $('.slider').slider({
                              duration: <?php echo $cs['c_duration'] ?>,
                              interval: <?php echo $cs['c_interval'] ?>,
                              height: <?php echo $cs['c_height'] ?>,
                              indicators: <?php echo $cs['c_indicators'] ?>
                         })
                    });
                    </script>
                    
                    <?php
               }
               elseif($pg['landing_image'] > '') {
                    ?>
                    <div class="row">
                    <?php
                    if($st->getStyle('landing_width') == 'f') {
                         ?>
                         <div class="col s12 m12 l12">
                         
                         <?php
                    } else {
                         ?>
                         <div class="col s12 m6 l6 offset-m3 offset-l3">
                         
                         <?php
                    }
                    ?>
                    <img class="responsive-img waves-effect waves-light <?php if($st->getStyle('landing_width') == 'c') { echo $st->getStyle('landing_shadow_depth'); } ?>" style="width: 100%;" src="<?php echo $g['site_url'] ?>/content/assets/landing_images/<?php echo $pg['landing_image'] ?>" />
                    </div>
                    </div>
                    
                    <?php
               }
               echo '<div ';
               if($fullwidth == 1) {
                    echo 'id="content"';
               }
               echo '><div class="section"><div class="row"><div class="col s12 m12 l12">';
               if($pg['plugin_id'] > 0) {
                    $plugin = $plug->getPluginFile($pg['plugin_id']);
                    $plugin = $plugin .'_plugin.php';
                    include 'content/plugins/'. $plugin;
               } else {
                    if($plug->findInlinePlugin($pg['section_content']) === true) {
                         echo $plug->pluginAndContent($pg['section_content']);
                    } else {
                         echo $pg['section_content'];
                    }
               }
               echo '</div></div></div></div>';
          }
     }
     ?>
     
     </main>
  
  

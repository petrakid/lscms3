     
     <main>
     
     <?php
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
                    if($cs['c_fullWidth'] == 1) {
                         $full = 'carousel-slider';
                         $fullj = '.carousel-slider';
                    } else {
                         $full = '';
                         $fullj = '';
                    }               
                    echo '<div class="carousel '. $full .'">';;
                    $slides = $car->carouselSlides(0);
                    $num = 1;
                    while($sl = $slides->fetch(PDO::FETCH_ASSOC)) {
                         if($sl['cs_type'] == 2) {
                              ?>
                              <div class="carousel-fixed-item center">
                              <!-- future enhancement-->
                              </div>
                              <?php
                         } else {
                              if($sl['cs_link'] > '') {
                                   ?>
                                   <a class="carousel-item" href="<?php echo $sl['cs_link'] ?>" target="<?php echo $sl['cs_target'] ?>">
                                   <?php
                              } else {
                                   ?>
                                   <a class="carousel-item" href="#<?php echo $num ?>!">
                                   <?php
                              }
                              ?>
                              <img src="<?php echo $g['site_url'] ?>/content/assets/carousel/<?php echo $sl['cs_image'] ?>" /></a>
                                             
                              <?php
                         }
                         $num++;
                    }
                    echo '</div>';
                    ?>
                    
                    <script>
                    $(function() {
                         $('.carousel<?php echo $fullj ?>').carousel({
                              duration: <?php echo $cs['c_duration'] ?>,
                              dist: <?php echo $cs['c_dist'] ?>,
                              shift: <?php echo $cs['c_shift'] ?>,
                              padding: <?php echo $cs['c_padding'] ?>,
                              numVisible: <?php echo $cs['c_numVisible'] ?>,
                              fullWidth: <?php echo $cs['c_fullWidth'] ?>,
                              indicators: <?php echo $cs['c_indicators'] ?>,
                              noWrap: <?php echo $cs['c_noWrap'] ?>,
                         })
                    });
                    </script>
                    
                    <?php
               }
               echo '<div class="section"><div class="row"><div class="col s12 m12 l12">'. $pg['section_content'] .'</div></div></div>';
          }
     }
     ?>
     
     </main>
  
  
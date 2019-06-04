<body>
<header>
     <?php
     if(isset($_SESSION['isLoggedIn'])) {
          echo $a->getActionbutton();
          ?>
          
          <script>
          $(function() {
               $('.fixed-action-btn').floatingActionButton({
                    toolbarEnabled: true
               });
               $('.tooltipped').tooltip();
          });          
          </script>
          
          <?php
     }
     echo $m->dropdownBuild();
     if($b->getBlockValue('navf') == 'f') {
          echo '<div class="navbar-fixed">';
     }
     ?>
     
     <nav class="<?php echo $b->getBlockValue('navc') ?> <?php echo $b->getBlockValue('navcc') ?>" style="height: <?php echo $b->getBlockValue('nav') ?>px; font-size: <?php echo $b->getBlockValue('navt') ?>px; color: <?php echo $b->getBlockValue('navtc') ?>;" role="navigation">
          <div class="nav-wrapper container">
          <a id="logo-container" href="<?php echo $g['site_url'] .'/'. $g['homepage'] ?>" class="brand-logo white-text <?php if($b->getBlockValue('navm') == 'l') { echo 'right'; } ?>"><?php echo $g['site_name'] ?></a>          
          <a href="#!" data-target="nav-mobile" class="sidenav-trigger button-collapse"><i class="material-icons">menu</i></a>          
          
          <ul class="<?php if($b->getBlockValue('navm') == 'r') { echo 'right';} else { echo 'left';} ?> hide-on-med-and-down">
               <?php
               $parent = $m->getMenu();
               while($par = $parent->fetch(PDO::FETCH_ASSOC)) {
                    $child = $m->getChild($par['m_id']);
                    if($child->rowCount() > 0) {
                         ?>
                         <li><a class="dropdown-trigger white-text" href="#!" data-target="dropdown<?php echo $par['m_id'] ?>"><?php echo stripslashes($par['menu_name']) ?><i class="material-icons right">arrow_drop_down</i></a></li>
                         <?php
                    } else {
                         ?>
                         <li><a class="white-text" href="<?php echo $g['site_url'] .'/'. $par['menu_link'] ?>"><?php echo stripslashes($par['menu_name']) ?></a></li>
                         <?php
                    }
               }
               
               ?>
          </ul>

          <ul id="nav-mobile" class="sidenav">          
               <?php echo $m->getMobileMenu() ?>
               
          </ul>

          </div>
     </nav>
     
     <?php
     if($b->getBlockValue('navf') == 'f') {
          echo '</div>';
     }
     ?>
          
</header>


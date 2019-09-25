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
     
     <nav <?php if($st->getStyle('use_navbar_color') == 0) { echo 'class="'. $b->getBlockValue('navc') .' '. $b->getBlockValue('navcc') .'"';} ?> style="<?php if($st->getStyle('use_navbar_color') == 1) { echo 'background-color: '. $st->getStyle('navbar_color') .'; ';} ?> height: <?php echo $b->getBlockValue('nav') ?>px; font-size: <?php echo $b->getBlockValue('navt') ?>px; color: <?php echo $b->getBlockValue('navtc') ?>;" role="navigation">
          <div class="nav-wrapper container">
          <?php
          if($st->getStyle('page_title') == 'l') {
               ?>
               <a href="#!" class="brand-logo valign-wrapper"><img class="site-logo" style="height: calc(<?php echo $b->getBlockValue('nav') ?>px - 8px);" src="<?php echo $g['site_url'] ?>/content/assets/logos/<?php echo $st->getStyle('site_logo') ?>" /></a>
               
               <?php
          } else {
               ?>
               <a id="logo-container" href="<?php echo $g['site_url'] .'/'. $g['homepage'] ?>" class="brand-logo <?php if($b->getBlockValue('navm') == 'l') { echo 'right'; } ?>" style="<?php echo $st->getStyle('title_font') ?> color: <?php echo $st->getStyle('title_font_color') ?>"><?php echo $g['site_name'] ?></a>                
               
               <?php
          }
          ?>       
          <a href="#!" data-target="nav-mobile" class="sidenav-trigger button-collapse"><i class="material-icons">menu</i></a>          
          
          <ul class="<?php if($b->getBlockValue('navm') == 'r') { echo 'right';} else { echo 'left';} ?> hide-on-med-and-down">
               <?php
               $parent = $m->getMenu();
               while($par = $parent->fetch(PDO::FETCH_ASSOC)) {
                    $child = $m->getChild($par['m_id']);
                    if($child->rowCount() > 0) {
                         ?>
                         <li><a class="dropdown-trigger white-text" href="#!" style="<?php echo $st->getStyle('parent_menu_font') ?> color: <?php echo $st->getStyle('parent_font_color') ?> !important;" data-target="dropdown<?php echo $par['m_id'] ?>"><?php echo stripslashes($par['menu_name']) ?><i class="material-icons right">arrow_drop_down</i></a></li>
                         <?php
                    } else {
                         ?>
                         <li><a class="white-text" href="<?php echo $g['site_url'] .'/'. $par['menu_link'] ?>" style="<?php echo $st->getStyle('parent_menu_font') ?> color: <?php echo $st->getStyle('parent_font_color') ?> !important;"><?php echo stripslashes($par['menu_name']) ?></a></li>
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


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
     ?>
     <nav class="white" role="navigation">
          <div class="nav-wrapper container">
          
          <?php
          $logo =  $l->getLayout(2);
          $log = $logo->fetch(PDO::FETCH_ASSOC);
          echo '<a id="logo-container" href="'. $g['site_url'] .'/'. $g['homepage'] .'" class="brand-logo '. $log['layout_class'] .'">'. $log['layout_content'] .'</a>';
          ?>
          
          <a href="#!" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>          
          
          <ul class="right hide-on-med-and-down">
               <?php
               $parent = $m->getMenu();
               while($par = $parent->fetch(PDO::FETCH_ASSOC)) {
                    $child = $m->getChild($par['m_id']);
                    if($child->rowCount() > 0) {
                         ?>
                         <li><a class="dropdown-trigger" href="#!" data-target="dropdown<?php echo $par['m_id'] ?>"><?php echo stripslashes($par['menu_name']) ?><i class="material-icons right">arrow_drop_down</i></a></li>
                         <?php
                    } else {
                         ?>
                         <li><a href="<?php echo $g['site_url'] .'/'. $par['menu_link'] ?>"><?php echo stripslashes($par['menu_name']) ?></a></li>
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
</header>


<?php
include 'content/a-head.php';

if(isset($_SESSION['isLoggedIn'])) {
     include 'content/a-menu.php';
}

if(isset($_SESSION['isLoggedIn'])) {
     if($_GET['f'] > '') {
          switch($_GET['f']) {
               case 'edit-page':              
                    include 'content/pages/edit-page.inc.php';
                    break;
               default:
                    break;
          }
     } else {
          include 'content/a-page.php';
     }
} else {
     include 'content/a-login.php';
}

if(isset($_SESSION['isLoggedIn'])) {
     include 'content/a-foot.php';
}
?>
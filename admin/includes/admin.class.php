<?php
class Security
{
     private $db;
     private $c;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function checkLoad($s) {
          if(strpos($s, 'index.php') !== false) {
               return 0;
          } else {
               return 1;
          }
     }
     
     public function isLoggedIn($s) {
          if($s['isLoggedIn'] == 1) {
               return true;
          } else {
               return false;
          }
     }
     
     public function setSecurity($user) {
          $sec = $this->db->query("SELECT * FROM tbl_users WHERE user_id = '$user'");
          return($sec);
     }
     
     public function randPassword($len) {
          $alphabet = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789$#%*';
          $pass = array();
          $alphaLength = strlen($alphabet) - 1;
          for($i=0;$i<$len;$i++) {
               $n = rand(0, $alphaLength);
               $pass[] = $alphabet[$n];
          }
          return implode($pass);          
     }
     
     public function allowRegistration() {
          $reg = $this->db->query("SELECT allow_reg FROM tbl_globals WHERE g_id = 1");
          $r = $reg->fetch(PDO::FETCH_ASSOC);
          if($r['allow_reg'] == 1) {
               return true;
          } else {
               return false;
          }
     }
     
     public function allowResetPass() {
          $pas = $this->db->query("SELECT allow_reset_pass FROM tbl_globals WHERE g_id = 1");
          $p = $pas->fetch(PDO::FETCH_ASSOC);
          if($p['allow_reset_pass'] == 1) {
               return true;
          } else {
               return false;
          }
     }
     
     public function allowRememberme() {
          $rem = $this->db->query("SELECT allow_remember_me FROM tbl_globals WHERE g_id = 1");
          $r = $rem->fetch(PDO::FETCH_ASSOC);
          if($r['allow_remember_me'] == 1) {
               return true;
          } else {
               return false;
          }          
     }
     
     public function checkCookie($c) {
          $c = $this->db->quote($c);
          $usr = $this->db->query("SELECT * FROM tbl_users WHERE cookie_hash = $c AND account_status = 1");
          if($usr->rowCount() == 1) {
               $row = $usr->fetch(PDO::FETCH_ASSOC);               
               $_SESSION['isLoggedIn'] = 1;
               $_SESSION['user'] = $row;
          }         
     }
     
     public function userList() {
          $list = $this->db->query("SELECT user_id, first_name, last_name, security_level, account_status, user_avatar, last_login FROM tbl_users WHERE account_status != 9 ORDER BY last_name");
          return $list;
     }
     
     public function getSecLevel($l) {
          $level = $this->db->query("SELECT security_level FROM tbl_user_security WHERE s_id = $l");
          $lvl = $level->fetch(PDO::FETCH_ASSOC);
          return $lvl['security_level'];
     }
     
     public function getAcctStatus($s) {
          switch($s) {
               case 1:
                    return 'Active';
                    break;
               case 0:
                    return 'Pending Activation';
                    break;
               case 2:
                    return 'Inactive';
                    break;
               default:
                    break;
          }
     }
}

class Admin
{
     private $db;
     public $usr;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     private function getGlobal($f) {
          $glb = $this->db->query("SELECT `$f` FROM tbl_globals WHERE g_id = 1");
          $g = $glb->fetch(PDO::FETCH_ASSOC);
          return $g[$f];
     }     
     
     public function getActionButton() {
          echo '
               <div class="fixed-action-btn toolbar" style="top: 1px; right: 15px;">
                 <a class="btn-floating btn-large red tooltipped" data-position="left" data-tooltip="Options">
                   <i class="large material-icons">settings</i>
                 </a>
                 <ul>
                 ';
                 if(!isset($_GET['quickedit'])) {
                    echo '<li><a class="waves-effect waves-light tooltipped" data-position="top" data-tooltip="Quick Edit" href="'. $_SERVER["REQUEST_URI"] .'&quickedit=1"><i class="material-icons">create</i></a></li>';
                 } else {
                    echo '<li><a class="waves-effect waves-light tooltipped" data-position="top" data-tooltip="Exit Quick Edit" onclick="exitQuickEdit(\''. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] .'\')"><i class="material-icons">exit_to_app</i></a></li>';
                 }
                 echo '<li><a class="waves-effect waves-light tooltipped" data-position="top" data-tooltip="Administration" href="https://'. $_SERVER["HTTP_HOST"] .'/admin/dashboard/"><i class="material-icons">settings_applications</i></a></li>
                   <li><a class="waves-effect waves-light tooltipped" data-position="top" data-tooltip="Account Management" href="https://'. $_SERVER["HTTP_HOST"] .'/admin/accountman/"><i class="material-icons">person</i></a></li>
                   <li><a class="waves-effect waves-light tooltipped" data-position="top" data-tooltip="Help & Support" href="https://'. $_SERVER["HTTP_HOST"] .'/admin/help/"><i class="material-icons">help</i></a></li>
                 </ul>
               </div>
      ';
     }
     
     public function getPageTitle($p) {
          return ucfirst($p);
     }
     
     public function getPageListP() {
          $list = $this->db->query("SELECT * FROM tbl_menu WHERE menu_parent_id = 0 AND menu_status != 9 ORDER BY menu_order");
          return $list;
     }
     
     public function getPageListC($pid) {
          $list = $this->db->query("SELECT * FROM tbl_menu WHERE menu_parent_id = $pid AND menu_status != 9 ORDER BY menu_order");
          return $list;
     }
     
     public function getParent($mid) {
          $parent = $this->db->query("SELECT menu_name FROM tbl_menu WHERE m_id = $mid");
          if($parent->rowCount() == 0) {
               return '';
          } else {
               $p = $parent->fetch(PDO::FETCH_ASSOC);
               return $p['menu_name'];
          }
     }
     
     public function getMenuForAdd() {
          $menu = $this->db->query("SELECT m_id, menu_name FROM tbl_menu WHERE menu_link != 'home' AND menu_parent_id = 0 ORDER BY menu_parent_id, menu_order");
          while($m = $menu->fetch(PDO::FETCH_ASSOC)) {
               echo '<option value="'. $m['m_id'] .'">'. stripslashes($m['menu_name']) .'</option>';
          }
     }
     
     public function getMenuOptions($mid) {
          echo '<a class="waves-effect waves-light teal darken-3 btn modal-trigger" href="/admin/pages/edit-page/'. $mid .'"><i class="material-icons left">create</i> Edit</a>
                <a class="waves-effect waves-light red darken-1 btn modal-trigger" href="#deletepagemodal" onclick="deleteConfirm('. $mid .')"><i class="material-icons left">delete</i> Delete</a>';
     }
     
     public function getSecLevels() {
          $level = $this->db->query("SELECT s_id, security_level FROM tbl_user_security WHERE s_id != 99 ORDER BY s_id");
          return $level;
     }
     
     public function getProfile($user) {
          $usr = $this->db->query("SELECT * FROM tbl_users WHERE user_id = '$user'");
          return $usr;
     }     
     
     public function editContent($pid) {
          $content = $this->db->query("SELECT tbl_menu.*, tbl_content.* FROM tbl_menu LEFT JOIN tbl_content ON tbl_menu.m_id = tbl_content.menu_id WHERE tbl_menu.m_id = $pid");
          return $content;
     }
     
     public function sendEmail($type, $email, $content) {
          switch($type) {
               case 1:
                    $to = $email;
                    $from = $this->getGlobal('no_reply_email');
                    $subject = "New Account Created.";
                    $message = "A new account has been created for you on the ". $this->getGlobal('site_name') ." website (". $this->getGlobal('site_url') .").\r\r\n\n<br /><br />You can log in by going to the website and clicking the login link.  Use your Email Address for your username and the following Password to log in:\r\r\n\n<br /><br />Your password is: ". $content ."\r\r\n\n<br /><br />Blessings!";
                    break;
               case 2:
                    $to = $email;
                    $from = $this->getGlobal('no_reply_email');
                    $subject = "Password Reset Initiated.";
                    $message = "Your password has been reset on the ". $this->getGlobal('site_name') ." website (". $this->getGlobal('site_url') .").\r\r\n\n<br /><br />Your NEW PASSWORD IS: ". $content ."\r\r\n\n<br /><br />Please log in with your new password.";
                    break;
               default:
                    break;
          }
          $headers = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' ."\r\n";
          $headers .= 'To: '. $to .' '. "\r\n";
          $headers .= 'From: '. $from .' '. "\r\n";
          mail($to, $subject, $message, $headers);
     }
}

class Plugins
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getPlugins() {
          $p = $this->db->query("SELECT * FROM tbl_plugins WHERE plugin_status = 1 ORDER BY plugin_order");
          return $p;
     }     
}
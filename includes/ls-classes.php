<?php
class Layout
{
     private $db;
     public $a;

     public function __construct(PDO $db) {
          $this->db = $db;
     }

     public function getLayout($a) {
          $layout = $this->db->query("SELECT * FROM tbl_layout WHERE layout_area = $a AND layout_status = 1");
          return $layout;
     }
}
class Menu
{
     private $db;
     public $mid;
     public $dmenu;
     public $mmenu;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function dropdownBuild() {
          $dmenu = '';
          $drop = $this->db->query("SELECT menu_link, m_id FROM tbl_menu WHERE menu_status = 1 AND menu_parent_id = 0 ORDER BY menu_order");
          while($d = $drop->fetch(PDO::FETCH_ASSOC)) {
               $drop2 = $this->db->query("SELECT menu_name, menu_link, m_id FROM tbl_menu WHERE menu_status = 1 AND menu_parent_id = $d[m_id] ORDER BY menu_order");
               if($drop2->rowCount() > 0) {
                    $dmenu .= '<ul id="dropdown'. $d['m_id'] .'" class="dropdown-content">'."\n";
                    while($d2 = $drop2->fetch(PDO::FETCH_ASSOC)) {
                         $dmenu .= '<li><a href="/'. $d['menu_link'] .'/'. $d2['menu_link'] .'">'. stripslashes($d2['menu_name']) .'</a></li>'."\n";
                    }
                    $dmenu .= '</ul>'."\n";
               }
          }
          return $dmenu;
     }
     
     public function getMenu() {
          $menu = $this->db->query("SELECT menu_name, menu_link, m_id FROM tbl_menu WHERE menu_parent_id = 0 AND menu_status = 1 ORDER BY menu_order");
          return $menu;
     }
     
     public function getChild($mid) {
          $child = $this->db->query("SELECT menu_name, menu_link, m_id FROM tbl_menu WHERE menu_parent_id = $mid AND menu_status = 1 ORDER BY menu_order");
          return $child;
     }
     
     public function getParent($pid) {
          $parent = $this->db->query("SELECT menu_link FROM tbl_menu WHERE m_id = $pid AND menu_parent_id = 0");
          if($parent->rowCount() > 0) {
               $p = $parent->fetch(PDO::FETCH_ASSOC);
               echo $p['menu_link'] .'/';
          } else {
               echo '';
          }
     }
     
     public function getMobileMenu() {
          $mmenu = '';
          $mobile = $this->db->query("SELECT menu_name, menu_link FROM tbl_menu WHERE menu_status = 1 ORDER BY menu_parent_id ASC, menu_order ASC");
          while($m = $mobile->fetch(PDO::FETCH_ASSOC)) {
               $mmenu .= '<li><a href="/'. $m['menu_link'] .'">'. stripslashes($m['menu_name']) .'</a></li>'."\n";
          }
          return $mmenu;
     }
}

class Page
{
     private $db;
     public $cont;
     public $page;
     public $menu;
     public $mid;
     public $a;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getContent($mid, $a) {
          if(strpos($mid, "/") !== false) {
               $exp = explode("/", $mid);
               $mid = $exp[1];
          }
          $menu = $this->db->query("SELECT m_id FROM tbl_menu WHERE menu_link = '$mid' AND menu_status = 1");
          if($menu->rowCount() > 0) {
               $m = $menu->fetch(PDO::FETCH_ASSOC);
               $content = $this->db->query("SELECT * FROM tbl_content WHERE menu_id = $m[m_id]");
          } else {
               $content = array();
          }
          return $content;
     }
     
     public function getPlugin($plid) {
          $plugin = $this->db->query("SELECT plugin_name FROM tbl_plugins WHERE pl_id = $plid AND plugin_status = 1");
          return $plugin;
     }
     
     public function getPageTitle($p) {
          $page = $this->db->query("SELECT tbl_content.page_title, tbl_menu.m_id FROM tbl_content LEFT JOIN tbl_menu ON tbl_content.menu_id = tbl_menu.m_id WHERE tbl_menu.menu_link = '$p' AND tbl_menu.menu_status != 0");
          if($page->rowCount() > 0) {
               $t = $page->fetch(PDO::FETCH_ASSOC);
               return $t['page_title'];
          } else {
               return null;
          }
     }
}

class Carousel
{
     private $db;
     private $settings;

     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function carouselSettings() {
          $s = $this->db->query("SELECT * FROM tbl_carousel_settings WHERE c_id = 1");
          return $s;
     }
     
     public function carouselSlides() {
          $c = $this->db->query("SELECT cs_image FROM tbl_carousel_slides WHERE cs_status = 1 ORDER BY cs_order");
          return $c;
     }
}
?>
<?php
class Blocks
{
     private $db;
     public $a;

     public function __construct(PDO $db) {
          $this->db = $db;
     }

     public function getBlockContent($a) {
          $block = $this->db->query("SELECT * FROM tbl_blocks WHERE block_area = '$a' AND block_status = 1");
          return $block;
     }
	
	public function formatPhone($phone) {
		if(!isset($phone{3})) { return ''; } 
     	$phone = preg_replace("/[^0-9]/", "", $phone);
     	$length = strlen($phone);
     	switch($length) {
          	case 7:
               	return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
               	break;
          	case 10:
				return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
               	break;
          	case 11:
               	return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
               	break;
          	default:
               	return $phone;
               	break;
		}	
	}
     
     public function getBlockLayout() {
          return $layout = $this->db->query("SELECT * FROM tbl_blocks WHERE block_status = 1");
     }
     
     public function getBlockValue($block) {
          $item = $this->db->query("SELECT block_content FROM tbl_blocks WHERE block_area = '$block'");
          $it = $item->fetch(PDO::FETCH_ASSOC);
          return $it['block_content'];
     }
        
     public function getTemplateColors($current) {
          $colors = array("red", "pink", "purple", "deep-purple", "indigo", "blue", "light-blue", "cyan", "teal", "green", "light-green", "lime", "yellow", "amber", "orange", "deep-orange", "brown", "grey");
          foreach($colors AS $color) {
               if($current == $color) {
                    echo '<option value="'. $color .'" selected="selected" style="background-color: '. $color .'">'. ucfirst($color) .'</option>';
               } else {
                    echo '<option value="'. $color .'" style="background-color: '. $color .'">'. ucfirst($color) .'</option>';
               }
          }
     }
     
     public function getTemplateShades($current) {
          $shades = array("lighten-5", "lighten-4", "lighten-3", "lighten-2", "lighten-1", "default", "darken-1", "darken-2", "darken-3", "darken-4");
          foreach($shades AS $shade) {
               if($current == $shade) {
                    echo '<option value="'. $shade .'" selected="selected" class="'. $this->getBlockValue('navc') .' '. $this->getBlockValue('navcc') .'">'. $shade .'</option>';
               } else {
                    echo '<option value="'. $shade .'" class="'. $shade .' '. $this->getBlockValue('navc') .'">'. $shade .'</option>';                    
               }
          }
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
			return $content;
          } else {
			return 0;
          }
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
     
     public function carouselSlides($all) {
          if($all == 1) {
               $s = '`cs_status` != 9';
          } else {
               $s = '`cs_status` = 1';
          }
          $c = $this->db->query("SELECT * FROM tbl_carousel_slides WHERE $s ORDER BY cs_order");
          return $c;
     }
}

class Social
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function isEnabled() {
          $api = $this->db->query("SELECT sm_api_key FROM tbl_social_media WHERE sm_id = 1");
          $a = $api->fetch(PDO::FETCH_ASSOC);
          if($a['sm_api_key'] > '') {
               return true;
          } else {
               return false;
          }
     }
     
     public function getID() {
          $sid = $this->db->query("SELECT sm_profile_id FROM tbl_social_media WHERE sm_id = 1");
          $s = $sid->fetch(PDO::FETCH_ASSOC);
          return $s['sm_profile_id'];
     }
}

class Plugin
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getPluginFile($p) {
          $plg = $this->db->query("SELECT plugin_link FROM tbl_plugins WHERE pl_id = $p AND plugin_status = 1");
          $pl = $plg->fetch(PDO::FETCH_ASSOC);
          $exp = explode("-", $pl['plugin_link']);
          return $exp[0];
     }   
     
     public function findInlinePlugin($c) {
          if(strpos($c, '[plugin') !== false) {
               return true;
          } else {
               return false;
          }
     }
     
     public function pluginAndContent($content) {
          echo $content;
          $needle = '[plugin';
          $cont = explode("[plugin", $content);
          foreach($cont AS $c) {
               
          }
     }
     
}
?>

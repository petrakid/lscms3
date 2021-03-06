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
     public $sts;
     
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
                         $dmenu .= '<li><a href="/'. $d['menu_link'] .'/'. $d2['menu_link'] .'" style="'. $this->getStyles('child_menu_font') .' color: '. $this->getStyles('child_font_color') .' !important;">'. stripslashes($d2['menu_name']) .'</a></li>'."\n";
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

     public function getStyles($f) {
          $sts = new Style($this->db);
          return $sts->getStyle($f);
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
               $content = $this->db->query("SELECT tbl_menu.menu_link, tbl_content.* FROM tbl_menu LEFT OUTER JOIN tbl_content ON tbl_menu.m_id = tbl_content.menu_id WHERE tbl_content.menu_id = $m[m_id]");
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

class Style
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getStyle($f) {
          $style = $this->db->query("SELECT `$f` FROM tbl_style WHERE st_id = 1");
          $st = $style->fetch(PDO::FETCH_ASSOC);
          return $st[$f];
     }
     
     public function getWebFonts($item) {
          $font = $this->db->query("SELECT `$item` FROM tbl_style WHERE st_id = 1");
          $ft = $font->fetch(PDO::FETCH_ASSOC);
          $name = explode(":", $ft[$item]);
          $name = explode(";", $name[1]);
          $name = ltrim($name[0], " ");
          return $name;          
     }     
}

class Resources
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getResources($page) {
          $f = 1;
          $res = $this->db->query("SELECT * FROM tbl_downloads WHERE download_page_id = $page AND download_status = 1 ORDER BY download_date_added DESC");
          while($r = $res->fetch(PDO::FETCH_ASSOC)) {
               if($r['download_security_level'] != 0) {
                    if(isset($_SESSION['isLoggedIn']) && ($_SESSION['user']['security_level'] >= $r['download_security_level'])) {
                         ?>
                         <tr>
                         <td><?php echo $this->getFileIcon($r['download_type']) ?></td>
                         <td><a href="<?php echo $_SESSION['site_url'] ?>/content/assets/uploads/<?php echo $r['download_filename'] ?>" download="myFile_<?php echo $f ?>.<?php echo strtolower($r['download_type']) ?>" onclick="addResourceCount('<?php echo $r['d_id'] ?>')"><?php echo stripslashes($r['download_name']) ?></a></td>
                         <td><?php echo strtoupper($r['download_type']) ?></td>
                         <td><?php echo strtoupper($r['download_count']) ?></td>
                         </tr>                         
                         
                         <?php
                    }
               } else {
                    ?>
                    <tr>
                    <td><?php echo $this->getFileIcon($r['download_type']) ?></td>
                    <td><a href="<?php echo $_SESSION['site_url'] ?>/content/assets/uploads/<?php echo $r['download_filename'] ?>" download="myFile_<?php echo $f ?>.<?php echo strtolower($r['download_type']) ?>" onclick="addResourceCount('<?php echo $r['d_id'] ?>')"><?php echo stripslashes($r['download_name']) ?></a></td>
                    <td><?php echo strtoupper($r['download_type']) ?></td>
                    <td><?php echo strtoupper($r['download_count']) ?></td>
                    </tr>                       
                    
                    <?php
               }
               $f++;
          }
     }

     public function getFileIcon($type) {
          switch(strtolower($type)) {
               case 'docx':
               case 'doc':
                    return '<i class="medium far fa-file-word blue-text lighten-1"></i>';
                    break;
               case 'ppt':
               case 'pptx':
                    return '<i class="medium far fa-file-powerpoint orange-text"></i>';
                    break;
               case 'xls':
               case 'xlsx':
                    return '<i class="medium far fa-file-excel green-text lighten-1"></i>';
                    break;
               case 'pdf':
                    return '<i class="medium far fa-file-pdf pink-text"></i>';
                    break;
               case 'txt':
               case 'rtf':
                    return '<i class="medium far fa-file-alt grey-text lighten-1"></i>';
                    break;
               case 'zip':
               case 'rar':
               case 'tar':
               case 'gz':
                    return '<i class="medium far fa-file-archive yellow-text"></i>';
                    break;
               case 'mp3':
               case 'ogg':
               case 'wav':
               case 'wma':
               case 'aiff':
               case 'aac':
               case 'm4a':
                    return '<i class="medium far fa-file-audio blue-text lighten-1"></i>';
                    break;
               case 'avi':
               case 'flv':
               case 'wmv':
               case 'mov':
               case 'mp4':
               case 'mpg':
                    return '<i class="medium far fa-file-video blue-text"></i>';
                    break;
               case 'jpg':
               case 'jpeg':
               case 'png':
               case 'gif':
               case 'tiff':
               case 'tif':
                    return '<i class="medium far fa-file-image orange-text lighten-1"></i>';
                    break;               
               default:
                    return '<i class="medium far fa-file grey-text"></i>';
                    break;
          }
     }     
}

class Calendar
{
     private $db;
     public $page;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function showAdminRow($page) {
          ?>
          <div class="row">
          <div class="col s12">
          <div class="card-panel amber">
          <h4 class="card-title">Settings</h4>
          <p>Select the Calendar that you would like to show on this page.  To change other settings, go to the <a href="/admin/events-manager/">Events Manager Feature</a> in Administration.</p>
          <div class="row">
          <div class="input-field col s4">
          <select id="es_id" onchange="selectCalendar(this.value, <?php echo $page ?>)">
          <option value="0" selected>Choose a Calendar</option>
          <?php
          $cl = $this->db->query("SELECT es_id, event_calendar_name, event_calendar_page_id FROM tbl_events_calendar_settings WHERE event_calendar_status = 1 ORDER BY event_calendar_name");
          while($c = $cl->fetch(PDO::FETCH_ASSOC)) {
               if($c['event_calendar_page_id'] == $page) {
                    echo '<option value="'. $c['es_id'] .'" selected="selected">'. $c['event_calendar_name'] .'</option>';
               } else {
                    echo '<option value="'. $c['es_id'] .'">'. $c['event_calendar_name'] .'</option>';
               }
          }
          
          ?>
          </select>
          </div>
          </div>
          </div>
          </div>
          </div>
          
          <?php
     }
     
     public function getCalendar($page) {
          $ca = $this->db->query("SELECT es_id, event_calendar_layout FROM tbl_events_calendar_settings WHERE event_calendar_page_id = $page AND event_calendar_status = 1");
          if($ca->rowCount() == 0) {
               ?>
               <div class="row">
               <div class="col s12">
               <div class="card-panel">
               <p>No calendar is active on this page.</p>
               </div>
               </div>
               </div>
               
               <?php
          } else {
               $c = $ca->fetch(PDO::FETCH_ASSOC);
               if($c['event_calendar_layout'] == 1) {
                    $res = $this->showGridCalendar($c['es_id']);
               } else {
                    $res = $this->showListCalendar($c['es_id']);
               }
               return $res;
          }
     }
     
     public function showGridCalendar($c) {
          $cal = $this->db->query("SELECT * FROM tbl_events_calendar WHERE event_status = 1 AND event_calendar_id = $c ORDER BY event_start_date DESC, event_start_time DESC");
          ?>
          <div class="row">
          <div class="col s12 m12 l12">
          <div id="calendar">
          
          </div>
          </div>
          </div>
          
          <?php
     }
     
     public function showListCalendar($c) {
          $cal = $this->db->query("SELECT * FROM tbl_events_calendar WHERE event_start_date >= now() AND event_end_date >= CURDATE() AND event_end_time >= now() AND event_status = 1 AND event_calendar_id = $c ORDER BY event_start_date ASC, event_start_time ASC LIMIT 20");          
          ?>
          <div class="row">
          <?php
          while($cl = $cal->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <div class="col s12 m12">
               <div class="card horizontal">
               <div class="card-image col s4 m2"><img src="<?php echo $_SESSION['site_url'] ?>/content/assets/events/<?php echo $cl['event_featured_image'] ?>" class="responsive z-depth-2" /></div>
               <div class="card-stacked">
               <div class="card-content">
               <div class="row">
               <div class="col s12"><h4 class="header"><?php echo stripslashes($cl['event_name']) ?></h4></div>
               <div class="col s4 m4">
               <strong>Date: <?php echo date('l, F jS, Y', strtotime($cl['event_start_date']))?></strong><br />
               <strong>Time: <?php echo date('g:ia (T)', strtotime($cl['event_start_time'])) ?></strong>
               </div>
               <div class="col s4 m4">
               <?php
               if($cl['event_repeated'] == 1) {
                    ?>
                    <strong>Repeated: YES</strong><br />
                    <strong>Repeated 
                    <?php
                    switch($cl['event_repeat_type']) {
                         case 1:
                              echo 'Daily';
                              break;
                         case 2:
                              echo 'Weekly';
                              break;
                         case 3:
                              echo 'Bi-Weekly';
                              break;
                         case 4:
                              echo 'Monthly on the Same Weekday';
                              break;
                         case 5:
                              echo 'Monthly on the Same Day';
                              break;
                         case 6:
                              echo 'Annually';
                              break;
                         default:
                              echo 'Unknown';
                              break;
                    }
                    echo ' until '. date('l, F jS, Y', strtotime($cl['event_end_date']));
                    ?>
                    </strong>
                    
                    <?php
               }
               ?>
               </div>
               <div class="col s4 m4">
               <?php
               $pnb = $this->db->query("SELECT allow_bookings, allow_payments FROM tbl_events_calendar_settings WHERE es_id = $cl[event_calendar_id]");
               $pb = $pnb->fetch(PDO::FETCH_ASSOC);
               if($pb['allow_bookings'] == 1 && $cl['event_bookings_enabled'] == 1) {
                    ?>
                    <strong>Registration: YES</strong><br />
                    <?php
               }
               if($pb['allow_payments'] == 1 && $cl['event_payments_enabled'] == 1) {
                    ?>
                    <strong>Pay Online: YES</strong>
                    <?php
               }
               ?>
               </div>
               </div>
               <div class="row">
               <div class="col 12 m8">
               <?php echo stripslashes(mb_strimwidth($cl['event_description'], 0, 250, ' ...Click Read More to continue')) ?>
               </div>
               <div class="col s12 m4">
               
               </div>
               </div>
               </div>
               <div class="card-action"><a href="#eventDetails" class="modal-trigger" onclick="readMoreEvent(<?php echo $cl['ev_id'] ?>)">Read More</a></div>
               </div>
               </div>
               </div>
               
               <?php
          }
          ?>
          
          </div>
          
          <?php
     }
}
?>

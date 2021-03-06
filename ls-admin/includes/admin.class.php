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
               <div class="fixed-action-btn toolbar" style="left: 15px; bottom: 10px; z-index: 9999;">
                 <a class="btn-floating btn-large red tooltipped" data-position="top" data-tooltip="Options">
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
                   <li><a class="waves-effect waves-light tooltipped" data-position="top" data-tooltip="Account Management" href="https://'. $_SERVER["HTTP_HOST"] .'/admin/profile/"><i class="material-icons">person</i></a></li>
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
     
     public function getParentMenu($nothome) {
          if($nothome == 1) {
               $filter = " AND menu_link != 'home'";
          } else {
               $filter = '';
          }
          $menu = $this->db->query("SELECT m_id, menu_name FROM tbl_menu WHERE menu_status != 9 AND menu_parent_id = 0 $filter ORDER BY menu_order");
          return $menu;
     }
     
     public function getChildMenu($c) {
          $menu = $this->db->query("SELECT m_id, menu_name FROM tbl_menu WHERE menu_status != 9 AND menu_parent_id = $c ORDER BY menu_order");
          return $menu;
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
	
	public function selectHomepage($current) {
		$home = $this->db->query("SELECT menu_link, menu_name FROM tbl_menu WHERE menu_status = 1 AND menu_link != '$current' ORDER BY menu_parent_id, menu_order");
		while($h = $home->fetch(PDO::FETCH_ASSOC)) {
			echo '<option value="'. $h['menu_link'] .'">'. $h['menu_name'] .'</option>';
		}
	}
	
	public function getStates($curr) {
		$us_state_abbrevs_names = array(
			'AL'=>'ALABAMA',
			'AK'=>'ALASKA',
			'AS'=>'AMERICAN SAMOA',
			'AZ'=>'ARIZONA',
			'AR'=>'ARKANSAS',
			'CA'=>'CALIFORNIA',
			'CO'=>'COLORADO',
			'CT'=>'CONNECTICUT',
			'DE'=>'DELAWARE',
			'DC'=>'DISTRICT OF COLUMBIA',
			'FM'=>'FEDERATED STATES OF MICRONESIA',
			'FL'=>'FLORIDA',
			'GA'=>'GEORGIA',
			'GU'=>'GUAM GU',
			'HI'=>'HAWAII',
			'ID'=>'IDAHO',
			'IL'=>'ILLINOIS',
			'IN'=>'INDIANA',
			'IA'=>'IOWA',
			'KS'=>'KANSAS',
			'KY'=>'KENTUCKY',
			'LA'=>'LOUISIANA',
			'ME'=>'MAINE',
			'MH'=>'MARSHALL ISLANDS',
			'MD'=>'MARYLAND',
			'MA'=>'MASSACHUSETTS',
			'MI'=>'MICHIGAN',
			'MN'=>'MINNESOTA',
			'MS'=>'MISSISSIPPI',
			'MO'=>'MISSOURI',
			'MT'=>'MONTANA',
			'NE'=>'NEBRASKA',
			'NV'=>'NEVADA',
			'NH'=>'NEW HAMPSHIRE',
			'NJ'=>'NEW JERSEY',
			'NM'=>'NEW MEXICO',
			'NY'=>'NEW YORK',
			'NC'=>'NORTH CAROLINA',
			'ND'=>'NORTH DAKOTA',
			'MP'=>'NORTHERN MARIANA ISLANDS',
			'OH'=>'OHIO',
			'OK'=>'OKLAHOMA',
			'OR'=>'OREGON',
			'PW'=>'PALAU',
			'PA'=>'PENNSYLVANIA',
			'PR'=>'PUERTO RICO',
			'RI'=>'RHODE ISLAND',
			'SC'=>'SOUTH CAROLINA',
			'SD'=>'SOUTH DAKOTA',
			'TN'=>'TENNESSEE',
			'TX'=>'TEXAS',
			'UT'=>'UTAH',
			'VT'=>'VERMONT',
			'VI'=>'VIRGIN ISLANDS',
			'VA'=>'VIRGINIA',
			'WA'=>'WASHINGTON',
			'WV'=>'WEST VIRGINIA',
			'WI'=>'WISCONSIN',
			'WY'=>'WYOMING',
			'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
			'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
			'AP'=>'ARMED FORCES PACIFIC'
		);
		foreach($us_state_abbrevs_names AS $abv => $name) {
			if($abv == $curr) {
				echo '<option selected="selected" value="'. $abv.'">'. ucwords(strtolower($name)) .'</option>'."\n";
			} else {
				echo '<option value="'. $abv.'">'. ucwords(strtolower($name)) .'</option>'."\n";
			}
		} 		
	}
     
     public function getMenuOptions($mid) {
          echo '<a class="waves-effect waves-light teal darken-3 btn modal-trigger" href="/admin/pages/edit-page/'. $mid .'"><i class="material-icons left">create</i> Edit</a>
                <a class="waves-effect waves-light red darken-1 btn modal-trigger" href="#deletepagemodal" onclick="deleteConfirm('. $mid .')"><i class="material-icons left">delete</i> Delete</a>';
     }
     
     public function getTimeZones($zone) {
          $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
          foreach($timezones AS $tz) {
               if($tz == $zone) {
                    echo '<option value="'. $zone .'" selected="selected">'. $tz .'</option>';
               } else {
                    echo '<option value="'. $tz .'">'. $tz .'</option>';
               }
          }
     }
     
     public function getSecLevels() {
          $level = $this->db->query("SELECT s_id, security_level FROM tbl_user_security WHERE s_id != 99 ORDER BY s_id");
          return $level;
     }
     
     public function getProfile($user) {
          $usr = $this->db->query("SELECT * FROM tbl_users WHERE user_id = '$user'");
          return $usr;
     }     
     
     public function editContent($mid) {
          $content = $this->db->query("SELECT tbl_menu.*, tbl_content.* FROM tbl_menu LEFT JOIN tbl_content ON tbl_menu.m_id = tbl_content.menu_id WHERE tbl_content.menu_id = $mid");
          if($content->rowCount() == 0) {
               $this->db->exec("INSERT INTO tbl_content (menu_id) VALUES ($mid)");
               $content = $this->db->query("SELECT tbl_menu.*, tbl_content.* FROM tbl_menu LEFT JOIN tbl_content ON tbl_menu.m_id = tbl_content.menu_id WHERE tbl_content.menu_id = $mid");
          }
          return $content;
     } 
     
     public function getStyleSettings() {
          $style = $this->db->query("SELECT * FROM tbl_style WHERE st_id = 1");
          $s = $style->fetch(PDO::FETCH_ASSOC);
          return $s;
     }
     
     public function getWebFonts($item) {
          $font = $this->db->query("SELECT `$item` FROM tbl_style WHERE st_id = 1");
          $ft = $font->fetch(PDO::FETCH_ASSOC);
          $name = explode(":", $ft[$item]);
          $name = explode(";", $name[1]);
          $name = ltrim($name[0], " ");
          echo $name;
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
         
     public function getWeeks($timestamp) {
          $month = date("m", strtotime($timestamp));
          $maxday    = date("t",$month);
          $thismonth = getdate(strtotime($timestamp));
          $timeStamp = mktime(0,0,0,$thismonth['mon'],1,$thismonth['year']);
          $startday  = date('w',$timeStamp);
          $day = $thismonth['mday'];
          $weeks = 0;
          $week_num = 0;
     
          for($i=0; $i<($maxday+$startday); $i++) {
               if(($i % 7) == 0){
                    $weeks++;
               }
               if($day == ($i - $startday + 1)){
                    $week_num = $weeks;
               }
          }
          switch($week_num) {
               case 1:
                    return 'first';
                    break;
               case 2:
                    return 'second';
                    break;
               case 3:
                    return 'third';
                    break;
               case 4:
                    return 'fourth';
                    break;
               case 5:
                    return 'fifth';
                    break;
               default:
                    return 'none';
                    break;
          }     
     }
}

class SocialMedia
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function socialApiStatus() {
          $api = $this->db->query("SELECT sm_api_key FROM tbl_social_media WHERE sm_id = 1");
          $a = $api->fetch(PDO::FETCH_ASSOC);
          if($a['sm_api_key'] > '') {
               return true;
          } else {
               return false;
          }
     }
     
     public function getProfileId() {
          $pro = $this->db->query("SELECT sm_profile_id FROM tbl_social_media WHERE sm_id = 1");
          $p = $pro->fetch(PDO::FETCH_ASSOC);
          return $p['sm_profile_id'];          
     }
}

class MailingLists
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getMailingLists() {
          $list = $this->db->query("SELECT * FROM tbl_mailings_lists WHERE list_status != 9 ORDER BY list_order");
          return $list;
     }
     
     public function getSubscribers($list) {
          if($list == 0) {
               $lists = "";
          } else {
               $lists = "WHERE `list_id` = $list";
          }
          $subs = $this->db->query("SELECT * FROM tbl_mailings_subscribers $lists ORDER BY last_name");
          return $subs;
     }
     
     public function getScheduledMailings() {
          $mailing = $this->db->query("SELECT m_id, mailing_subject, mailing_date FROM tbl_mailings WHERE mailing_status = 1 AND DATE(mailing_date) > DATE(now())");
          return $mailing;
     }
     
     public function getArchivedMailings() {
          $mailing = $this->db->query("SELECT m_id, mailing_subject, mailing_date FROM tbl_mailings WHERE mailing_status = 1 AND DATE(mailing_date) <= DATE(now())");
          return $mailing;          
     }
     
}

class SermonManager
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getSermonSettings() {
          $settings = $this->db->query("SELECT * FROM tbl_sermons_settings WHERE s_id = 1");
          return $settings;
     }
     
     public function getScriptureVersions($version) {
          $versions = $this->db->query("SELECT * FROM tbl_sermons_scripture_versions ORDER BY version_order");
          while($v = $versions->fetch(PDO::FETCH_ASSOC)) {
               echo '<option value="'. $v['version_code'] .'" ';
               if($v['version_code'] == $version) {
                    echo 'selected="selected"';
               }
               echo '>'. $v['version_name'] .'</option>'."\n";
          }
     }
     
     public function getPreachersSelect() {
          $prh = $this->db->query("SELECT pr_id, first_name, last_name, title FROM tbl_sermons_preachers WHERE preacher_status = 1 ORDER BY last_name");
          while($pr = $prh->fetch(PDO::FETCH_ASSOC)) {
               echo '<option value="'. $pr['pr_id'] .'">'. $pr['title'] .' '. $pr['first_name'] .' '. $pr['last_name'] .'</option>'."\n";
          }
     }
     
     public function getSeasons() {
          $sea = $this->db->query("SELECT * FROM tbl_sermons_seasons WHERE season_status = 1 ORDER BY season_order");
          return $sea;
     }
     
     public function getSeries() {
          $ser = $this->db->query("SELECT * FROM tbl_sermons_series WHERE series_status = 1 ORDER BY series_name");
          return $ser;
     }
     
     public function getSermons() {
          $sermons = $this->db->query("SELECT se_id, sermon_title, sermon_date, sermon_preacher_id, sermon_season_id, sermon_status, sermon_featured FROM tbl_sermons WHERE sermon_status != 9 ORDER BY sermon_date DESC");
          return $sermons;
     }
     
     public function getPreacher($p) {
          $preach = $this->db->query("SELECT title, first_name, last_name FROM tbl_sermons_preachers WHERE pr_id = $p");
          $p = $preach->fetch(PDO::FETCH_ASSOC);
          return $p['title'] .' '. $p['first_name'] .' '. $p['last_name'];
     }
     
     public function getSeason($s) {
          $season = $this->db->query("SELECT season_color FROM tbl_sermons_seasons WHERE se_id = $s");
          $c = $season->fetch(PDO::FETCH_ASSOC);
          return $c['season_color'];
     }
}

class Downloads
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getDownloads() {
          $down = $this->db->query("SELECT * FROM tbl_downloads WHERE download_status != 9 ORDER BY download_date_added DESC");
          $f = 1;
          while($d = $down->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <tr>
               <td style="width: 10%;"><?php echo $this->getFileIcon($d['download_type']) ?></td>
               <td style="width: 30%;"><a download="myFile_<?php echo $f ?>.<?php echo $d['download_type'] ?>" href="https://<?php echo $_SERVER["HTTP_HOST"] ?>/content/assets/uploads/<?php echo $d['download_filename'] ?>"><strong><?php echo stripslashes($d['download_name']) ?></strong></a><br />
                   Access: <?php echo $this->getAccess($d['download_security_level']); if($d['download_password'] > '') { echo ' / Password Protected'; } ?></td>
               <td style="width: 15%;"><?php echo strtoupper($d['download_type']) ?></td>
               <td style="width: 25%;" data-sort="<?php echo strtotime($d['download_date_added']) ?>">Added: <?php echo date('m/j/y h:i a', strtotime($d['download_date_added'])) ?><br />
                   Last: <?php echo date('m/j/y h:i a', strtotime($d['download_date_last'])) ?></td>
               <td style="text-align: left; width: 5%"><?php echo $d['download_count'] ?></td>
               <td style="width: 15%;"><?php echo $this->getPage($d['download_page_id']) ?></td>
               </tr>
               
               <?php
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
     
     public function getAccess($sec) {
          if($sec == 0) {
               return 'All Access';
          }
          $secs = $this->db->query("SELECT * FROM tbl_user_security WHERE s_id = $sec");
          $s = $secs->fetch(PDO::FETCH_ASSOC);
          return $s['security_level'];
     }
     
     public function getPage($page) {
          $pge = $this->db->query("SELECT tbl_menu.menu_name, tbl_menu.menu_link, tbl_content.p_id FROM tbl_menu LEFT OUTER JOIN tbl_content ON tbl_menu.m_id = tbl_content.menu_id WHERE tbl_content.p_id = $page AND tbl_menu.menu_status != 9");
          if($pge->rowCount() == 0) {
               return 'None';
          }
          $pg = $pge->fetch(PDO::FETCH_ASSOC);
          return '<a href="https://'. $_SERVER["HTTP_HOST"] .'/'. $pg['menu_link'] .'">'. stripslashes($pg['menu_name']) .'</a>';
     }
     
     public function getPages() {
          $pl = $this->db->query("SELECT pl_id FROM tbl_plugins WHERE plugin_link = 'download-manager' AND plugin_status = 1");
          $pg = $pl->fetch(PDO::FETCH_ASSOC);
          $pge = $this->db->query("SELECT tbl_menu.menu_name, tbl_content.p_id FROM tbl_menu LEFT OUTER JOIN tbl_content ON tbl_menu.m_id = tbl_content.menu_id WHERE tbl_menu.menu_status != 9 AND tbl_content.plugin_id = $pg[pl_id] ORDER BY tbl_menu.menu_name");
          while($pa = $pge->fetch(PDO::FETCH_ASSOC)) {
               echo '<option value="'. $pa['p_id'] .'">'. $pa['menu_name'] .'</option>';
          }
     }
     
     public function getSecurity() {
          $sc = $this->db->query("SELECT * FROM tbl_user_security ORDER BY s_id");
          while($s = $sc->fetch(PDO::FETCH_ASSOC)) {
               echo '<option value="'. $s['s_id'] .'">'. $s['security_level'] .'</option>';
          }
     }
}

class Events
{
     private $db;
     
     public function __construct(PDO $db) {
          $this->db = $db;
     }
     
     public function getCalendarList($cal = 0) {
          $list = $this->db->query("SELECT es_id, event_calendar_name FROM tbl_events_calendar_settings WHERE event_calendar_status != 9 ORDER BY es_id");
          while($l = $list->fetch(PDO::FETCH_ASSOC)) {
               echo '<option value="'. $l['es_id'] .'"';
               if($cal == $l['es_id']) {
                    echo 'selected="selected"';
               }
               echo '>'. $l['event_calendar_name'] .'</option>';
          }
     }
     
     public function getCalendar($calid) {
          $cal = $this->db->query("SELECT event_calendar_name FROM tbl_events_calendar_settings WHERE es_id = $calid");
          $cl = $cal->fetch(PDO::FETCH_ASSOC);
          return $cl['event_calendar_name'];
     }
     
     public function getCategories() {
          ?>
          <ul class="collection">
          <li class="collection-header">Categories</li>
          <?php
          $cat = $this->db->query("SELECT * FROM tbl_events_calendar_categories ORDER BY event_category_name");
          while($ct = $cat->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <li class="collection-item">
               <a href="#editCategory" onclick="editCategory(<?php echo $ct['ec_id'] ?>)" class="modal-trigger <?php echo $ct['event_category_color'] ?>-text"><?php echo $ct['event_category_name'] ?></a>
               </li>
               
               <?php
          }
          ?>
          <li class="collection-item"><a href="#newCategory" onclick="newCategory()" class="modal-trigger waves-effect waves-light btn blue"><i class="material-icons left">add</i>New Category</a></li>          
          </ul>
          
          <?php
     }
     
     public function selectCategories($cats = '') {
          if($cats > '') {
               $cts = explode(",", $cats);
          } else {
               $cts = array('0');
          }
          ?>
          <div class="col s6 m3">
          <span class="form-text">Categories</span>
          <?php
          $cat = $this->db->query("SELECT * FROM tbl_events_calendar_categories ORDER BY event_category_name");
          while($ct = $cat->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <p>
               <label>
               <input type="checkbox" name="event_category_ids" value="<?php echo $ct['ec_id'] ?>" <?php if(in_array($ct['ec_id'], $cts)) { echo 'checked="checked"';} ?> />
               <span class="<?php echo $ct['event_category_color'] ?>-text"><?php echo stripslashes($ct['event_category_name']) ?></span>
               </label>
               </p>
               
               <?php
          }
          ?>
          </div>
          
          <?php
     }
     
     public function getLocations() {
          ?>
          <ul class="collection">
          <li class="collection-header">Locations</li>
          <?php          
          $loc = $this->db->query("SELECT * FROM tbl_events_calendar_locations ORDER BY event_location_name");
          while($lc = $loc->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <li class="collection-item">
               <a href="#editLocation" onclick="editLocation(this.id)" class="modal-trigger" id="<?php echo $lc['el_id'] ?>"><?php echo $lc['event_location_name'] ?></a>
               </li>
               
               <?php
          }
          ?>
          <li class="collection-item"><a href="#newLocation" onclick="newLocation()" class="modal-trigger waves-effect waves-light btn green"><i class="material-icons left">add</i>New Location</a></li>
          </ul>
          
          <?php          
     }
     
     public function selectLocation($locs = '') {
          if($locs == '') {
               $locs = 0;
          }
          ?>
          <div class="col s6 m3">
          <span class="form-text">Location</span>          
          <?php
          $loc = $this->db->query("SELECT * FROM tbl_events_calendar_locations ORDER BY event_location_name");
          while($lc = $loc->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <p>
               <label class="tooltipped" data-position="right" data-tooltip="<?php echo $lc['event_location_address'] .' -- '. $lc['event_location_city'] .', '. $lc['event_location_state'] ?>">
               <input type="radio" name="event_location_id" value="<?php echo $lc['el_id'] ?>" <?php if($lc['el_id'] == $locs) { echo 'checked="checked"'; } ?> />
               <span><?php echo $lc['event_location_name'] ?> <?php echo $lc['event_location_room'] ?></span>
               </label>
               </p>
               
               <?php
          }
          ?>
          </div>
          
          <?php
     }
     
     public function getEventsList() {
          $evts = $this->db->query("SELECT * FROM tbl_events_calendar WHERE event_end_date >= CURDATE() AND event_end_time >= now() AND event_status != 9 ORDER BY event_start_date DESC, event_start_time DESC");
          while($ev = $evts->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <tr <?php if($ev['event_status'] == 0) { echo 'class="red lighten-3"';} ?>>
               <td><a href="#editEventModal" class="modal-trigger" onclick="editEvent('<?php echo $ev['ev_id'] ?>')">Edit</a></td>
               <td class="center-align" style="width: 15%;"><img class="responsive-img materialboxed" src="https://<?php echo $_SERVER["HTTP_HOST"] ?>/content/assets/events/<?php echo $ev['event_featured_image'] ?>" style="width: 100%; margin: 0 auto;" /></td>
               <td><?php echo stripslashes($ev['event_name']) ?></td>
               <td><?php echo date('m/j/Y', strtotime($ev['event_start_date'])) ?>, <?php echo date('h:i a', strtotime($ev['event_start_time'])) ?> until <?php echo date('m/j/Y', strtotime($ev['event_end_date'])) ?>, <?php echo date('h:i a', strtotime($ev['event_end_time'])) ?><br />
               <?php if($ev['event_repeated'] == 1) {
                    switch($ev['event_repeat_type']) {
                         case 0:
                              echo 'Not Repeated';
                              break;
                         case 1:
                              echo 'Repeated Daily';
                              break;
                         case 2:
                              echo 'Repeated Weekly';
                              break;
                         case 3:
                              echo 'Repeated Bi-Weekly';
                              break;
                         case 4:
                              echo 'Repeated Monthly (same weekday)';
                              break;
                         case 5:
                              echo 'Repeated Monthly (same day of month)';
                              break;
                         case 6:
                              echo 'Repeated Annually';
                              break;
                         default:
                              echo 'Not Repeated';
                              break;
                    }
               }
               ?>
               
               </td>
               <td><?php echo $this->getCalendar($ev['event_calendar_id']) ?></td>
               <td style="text-align: center;"><?php if($ev['event_bookings_enabled'] == 1) { echo '<i class="material-icons green-text" style="font-size: 40px">check</i>';} ?></td>
               <td style="text-align: center;"><?php if($ev['event_payments_enabled'] == 1) { echo '<i class="material-icons green-text" style="font-size: 40px">check</i>';} ?></td>
               </tr>
               
               <?php
          }
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

<?php
session_start();

include '../../includes/ls-config.php';

try {
     $dsn = "mysql:host=". DB_HOST .";dbname=". DB_NAME;
     $db = new PDO($dsn, DB_USER, DB_PASSWORD);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
     echo "Connection to Database failed: ". $e->getMessage();
}

$glb = $db->query("SELECT * FROM tbl_globals WHERE g_id = 1");
$g = $glb->fetch(PDO::FETCH_ASSOC);

include 'admin.class.php';
include '../../includes/ls-classes.php';
$a = new Admin($db);
$sec = new Security($db);
$b = new Blocks($db);
$d = new Downloads($db);
$evt = new Events($db);

if(!isset($_SESSION['isLoggedIn'])) {
     if(isset($_POST['validate_email'])) {
          $username = $db->quote($_POST['email_address']);
          $chk = $db->query("SELECT user_id FROM tbl_users WHERE user_id = $username");
          if($chk->rowCount() == 1) {
               echo 'true';
          } else {
               echo 'false';
          }
     }
     
     if(isset($_POST['user_login'])) {
          $username = $db->quote($_POST['username']);
          $chk = $db->query("SELECT user_id, password, salt, account_block, account_status FROM tbl_users WHERE user_id = $username AND account_status != 9");
          if($chk->rowCount() < 1) {
               echo 'No such user exists.  Please try again.';
               die;
          }
          
          $cpw = $chk->fetch(PDO::FETCH_ASSOC);
          
          if($cpw['account_status'] == 0) {
               echo 'This is your first time logging into the website.  BE SURE to change your password right away!<br /><br />';
          }
          
          if($cpw['account_block'] > 0) {
               echo 'Your account is blocked due to too many failed login attempts.  Contact the Administrator';
               die;
          }
          
          $password = hash('sha256', $_POST['password'] . $cpw['salt']);
          if($password != $cpw['password']) {
               echo 'Your password is wrong.  Please try again.';
               die;
          }
          
          if($_POST['rememberme'] == true) {
               $length = time() + (86400 * 30);
               $cookiehash = md5(sha1($cpw['user_id'] . $cpw['salt']));
               $db->exec("UPDATE tbl_users SET cookie_hash = '$cookiehash' WHERE user_id = $username");
               setcookie('remlog', $cookiehash, $length, "/");          
          }
          $user = $db->query("SELECT * FROM tbl_users WHERE user_id = $username");
          $u = $user->fetch(PDO::FETCH_ASSOC);
          $_SESSION['user'] = $u;
          $_SESSION['isLoggedIn'] = 1;
          $db->exec("UPDATE tbl_users SET last_login = now() WHERE user_id = $username");
          if($u['account_status'] == 0) {
               $db->exec("UPDATE tbl_users SET account_status = 1 WHERE user_id = $username");
          } else {
               echo '<div class="green lighten-1" style="text-align: center">Welcome!</div>';
               die;
          }
     }     
}

if(isset($_POST['change_my_pass'])) {
     ?>
     <h4 class="header">Change Password</h4>
     <div class="row">
     <div class="input-field col s12">
     <input type="password" name="my_old_password" id="my_old_password" placeholder="Old Password" onkeyup="checkMyPassword(this.value)" />
     <label for="my_old_password">Enter your Current Password</label>
     <span class="helper-text helper-text-custom" id="cpasswordRes"></span>
     </div>
     </div>
     <div class="row" id="newPassRow" style="display: none">
     <div class="input-field col s6">
     <input type="password" name="new_pass_1" id="new_pass_1" placeholder="Enter a New Password" onkeyup="checkStrength()" />
     <label for="new_pass_1">Enter a New Password</label>
     <span class="helper-text helper-text-custom" id="new_pass_1_check"></span>
     </div>
     <div class="input-field col s6">
     <input type="password" name="new_pass_2" id="new_pass_2" placeholder="Enter the New Password again" onkeyup="checkPasswords()" />
     <label for="new_pass_2">Enter the New Password again.</label>
     <span class="helper-text helper-text-custom" id="new_pass_2_check"></span>
     </div>
     <div class="space"></div>
     </div>
     <div class="space"></div>
     <div class="row">
     <div class="col s12">
     <a href="#!" onclick="updateMyPass()" id="reset_button" style="display: none;" class="btn waves-effect effect-light indigo white-text"><i class="material-icons left">security</i> Update</a>
     </div>
     </div>
     <?php
}

if(isset($_POST['check_my_pass'])) {
     $user = $db->query("SELECT password, salt FROM tbl_users WHERE user_id = '". $_SESSION['user']['user_id'] ."'");
     $pass = $user->fetch(PDO::FETCH_ASSOC);
     
     $password = hash('sha256', $_POST['password'] . $pass['salt']);
     if($password != $pass['password']) {
          echo '0';
     } else {
          echo '1';
     }
     
}

if(isset($_POST['update_my_pass'])) {
     $password = $_POST['password'];
     
     $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
     $newpass = hash('sha256', $password . $salt);
     
     $db->exec("UPDATE tbl_users SET password = '$newpass', salt = '$salt' WHERE user_id = '". $_SESSION['user']['user_id'] ."'");
     
     $a->sendEmail(2, $_SESSION['user']['user_id'], $password);
     
     echo '<h4 class="header">Password Changed Successfully!</h4><p style="margin: 10px;"><a href="#!" class="btn modal-close waves-effect waves-red">Close</a></p>';     
}

if(isset($_POST['view_my_messages'])) {
     
}

if(isset($_POST['send_message'])) {
     
}

if(isset($_POST['do_send_message'])) {
     
}

if(isset($_POST['change_my_avatar'])) {
     
}

if(isset($_POST['update_my_avatar'])) {
     
}

if(isset($_POST['close_my_account'])) {
     
}

if(isset($_POST['update_style'])) {
     if($_POST['f'] == 'use_navbar_color') {
          if($_POST['v'] == 'false') {
               $_POST['v'] = 0;
          } else {
               $_POST['v'] = 1;
          }

     }
     $db->exec("UPDATE tbl_style SET `$_POST[f]` = '$_POST[v]' WHERE st_id = 1");
     echo 'Updated';
}

if(isset($_POST['update_logo'])) {
     $extarray = array('jpg', 'jpeg', 'png');
     $root = $g['doc_root'];
     if($_FILES['site_logo']['name'] > '') {
          $ext = pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION);
          $path = $root .'content/assets/logos/';
          if(!in_array(strtolower($ext), $extarray)) {
               echo 'The Logo Image you added does not have a valid file extension.  Only jpg (jpeg) and png images allowed. Changes not saved.';
               die;
          }
          $_POST['site_logo'] = date('Ymdhis') . rand(1, 4) .'.'. $ext;
          if(!move_uploaded_file($_FILES['site_logo']['tmp_name'], $path . $_POST['site_logo'])) {
               echo 'The Logo Image did not upload correctly.';
               die;
          } else {
               $db->exec("UPDATE tbl_style SET site_logo = '$_POST[site_logo]' WHERE st_id = 1");
               echo 'Site Logo Updated';
          }
     }
}

if(isset($_POST['delete_logo'])) {
     $db->exec("UPDATE tbl_style SET site_logo = '' WHERE st_id = 1");
     echo 'Logo Removed';
}

if(isset($_POST['save_sm_key'])) {
     $db->exec("UPDATE tbl_social_media SET sm_api_key = '$_POST[sm_api_key]' WHERE sm_id = 1");
     echo 'Key Saved';
}

if(isset($_POST['save_sm_value'])) {
     $db->exec("UPDATE tbl_social_media SET `$_POST[f]` = '$_POST[v]' WHERE sm_id = 1");
     echo 'Updated';
}

if(isset($_POST['item'])) {
     $i = 0;
     foreach($_POST['item'] AS $value) {
          $db->exec("UPDATE tbl_carousel_slides SET cs_order = $i WHERE cs_id = $value");
          $i++;
     }
     echo 'Order Updated';
}

if(isset($_POST['mlist'])) {
     $i = 0;
     foreach($_POST['mlist'] AS $value) {
          $db->exec("UPDATE tbl_menu SET menu_order = $i WHERE m_id = $value");
          $i++;
     }
     echo 'Menu order updated successfully!';
}

if(isset($_POST['move_child'])) {
     $receiver = explode("-", $_POST['receiver_id']);
     $r = $receiver[1];
     $mid = explode("-", $_POST['m_id']);
     $m = $mid[1];
     
     $db->exec("UPDATE tbl_menu SET menu_parent_id = $r WHERE m_id = $m");
     echo 'Child menu moved to new location successfully. Drag it to its new order to finish.';
}

if(isset($_POST['clist'])) {
     if(!isset($_POST['move_child'])) {
          $i = 0;
          foreach($_POST['clist'] AS $value) {
               $db->exec("UPDATE tbl_menu SET menu_order = $i WHERE m_id = $value");
               $i++;
          }
          echo 'Child menu order updated successfully';
     } else {
          echo 'could work';
     } 
}

if(isset($_POST['update_carousel'])) {
     echo $_POST['value'];
     if($_POST['field'] == 'c_fullWidth') {
          if($_POST['value'] == 'true') {
               $_POST['value'] = 1;
          } else {
               $_POST['value'] = 0;
          }
     }
     $db->exec("UPDATE tbl_carousel_settings SET `$_POST[field]` = '$_POST[value]' WHERE c_id = 1");
     echo 'Carousel Updated';
}

if(isset($_POST['add_slide'])) {
     $extarray = array('jpg', 'jpeg', 'png', 'gif');
     $root = $g['doc_root'];
     if($_FILES['cs_image']['name'] > '') {
          $ext = pathinfo($_FILES['cs_image']['name'], PATHINFO_EXTENSION);
          $path = $root .'content/assets/carousel/';
          if(!in_array(strtolower($ext), $extarray)) {
               echo 'The Slide you tried to add is not a valid image.  Only jpg (jpeg), png and gif images allowed. Slide not added.';
               die;
          }
          $_POST['cs_image'] = date('Ymdhis') . rand(1, 4) .'.'. $ext;
          if(!move_uploaded_file($_FILES['cs_image']['tmp_name'], $path . $_POST['cs_image'])) {
               echo 'The slide did not upload correctly';
          } else {
               unset($_POST['add_slide']);
               $_POST['cs_status'] = 0;
               $_POST['cs_order'] = 0;
               $_POST['cs_link'] = '';
               $_POST['cs_type'] = 1;
               $_POST['cs_content'] = '';
               $sql = "INSERT INTO tbl_carousel_slides (";
               foreach($_POST AS $k => $v) {
                    $sql .= "`$k`, ";
               }
               $sql = rtrim($sql, ", ");
               $sql .= ') VALUES (';
               foreach($_POST AS $k => $v) {
                    $sql .= "'$v', ";
               }
               $sql = rtrim($sql, ", ");
               $sql .= ')';
               $db->exec($sql);
               echo 'Slide Added Successfully';               
          }
     } else {
          echo 'Somehow there was no file or file name with the upload...Strange.';
     }
}

if(isset($_POST['change_slide_link'])) {
     $slide = $db->query("SELECT cs_link, cs_target FROM tbl_carousel_slides WHERE cs_id = $_POST[cs_id]"); 
     $cs = $slide->fetch(PDO::FETCH_ASSOC);
     ?>
     <h4 class="title">Slide Link</h4>
     <input type="hidden" name="ucs_id" id="ucs_id" value="<?php echo $_POST['cs_id'] ?>" />
     <div class="row">
     <div class="input-field col s6">
     <input type="text" name="ucs_link" id="ucs_link" value="<?php echo $cs['cs_link'] ?>" />
     <label for="ucs_link" class="active">Slide Link</label>
     </div>
     <div class="input-field col s6">
     <select name="ucs_target" id="ucs_target">
     <option disabled selected>Select Target</option>
     <option value="_self" <?php if($cs['cs_target'] == '_self') { echo 'selected="selected"';} ?>>Self</option>
     <option value="_blank" <?php if($cs['cs_target'] == '_blank') { echo 'selected="selected"';}?>>New Window/Tab</option>
     </select>
     <label class="active">Link Target</label>
     </div>
     </div>
     <div class="row">
     <div class="col s12" style="text-align: right;">
     <a href="#!" class="btn waves-effect waves-light green white-text" onclick="updateSlideLink()"><i class="material-icons left">save</i> Update</a>
     </div>
     </div>
     <?php
}

if(isset($_POST['update_slide_link'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_link = '$_POST[cs_link]', cs_target = '$_POST[cs_target]' WHERE cs_id = $_POST[cs_id]");
     echo 'Updated';
}

if(isset($_POST['change_slide_type'])) {
     ?>
     <h4 class="title">Change Slide Type</h4>
     <p>This option is currently unavailable but will be added in a later update.</p>
     <?php
     echo 'Updated';     
}

if(isset($_POST['update_slide_type'])) {
     die;
}

if(isset($_POST['show_slide'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_status = 1 WHERE cs_id = $_POST[cs_id]");
     echo 'Updated';     
}

if(isset($_POST['hide_slide'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_status = 0 WHERE cs_id = $_POST[cs_id]");
     echo 'Updated';          
}

if(isset($_POST['remove_slide'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_status = 9 WHERE cs_id = $_POST[cs_id]");
     echo 'Updated';     
}

if(isset($_POST['edit_content'])) {
     $cont = $db->query("SELECT * FROM tbl_content WHERE menu_id = $_POST[menu_id]");
}

if(isset($_POST['save_edit'])) {
     $content = $db->quote($_POST['save_content']);
     $db->exec("UPDATE tbl_content SET section_content = $content WHERE p_id = $_POST[save_page] AND section_order = $_POST[save_section]");
     echo 'Updated';     
}

if(isset($_POST['save_quick_edit'])) {
     if($_POST['menu_link'] == '') {
          $_POST['menu_link'] = $g['homepage'];
     }
     $cp = $db->query("SELECT m_id FROM tbl_menu WHERE menu_link = '$_POST[menu_link]'");
     $c = $cp->fetch(PDO::FETCH_ASSOC);
     $p_id = $c['m_id'];
     $content = addslashes($_POST['content']);
     $db->exec("UPDATE tbl_content SET section_content = '$content' WHERE menu_id = $p_id");
     echo 'Updated';     
}

if(isset($_POST['new_menu_form'])) {
     ?>
     <h4>New Page</h4>
     <div class="col s12">
     <div class="row">
     <div class="input-field col s6">
     <input placeholder="Menu/Page Name" id="nmenu_name" type="text" onkeyup="makeFriendly(this.value)" />
     <label for="menu_name">Menu/Page Name</label>
     </div>
     <div class="input-field col s6">
     <input placeholder="Friendly Link" id="nmenu_link" type="text" readonly="true" class="active" />
     <label for="menu_link">Friendly Link</label>
     <span class="helper-text">Automatically created for SEO friendliness!</span>
     </div>
     </div>
     <div class="row">
     <div class="input-field col s6">
     <select id="nmenu_parent_id" onchange="getChildren(this.value)">
     <option value="0" selected>Choose a Parent</option>
     <?php echo $a->getMenuForAdd() ?>
     </select>
     <label>Parent Page (optional)</label>
     <span class="helper-text">If this menu should appear as a child of another menu. Otherwise, leave alone.</span>
     </div>
     <div class="input-field col s6">
     <select id="nmenu_order">
     <option value="0" disabled selected>Placement</option>
     
     </select>
     <label>Menu Order</label>
     </div>
     </div>
     <div class="row">
     <div class="input-field col s6"></div>
     <div class="col s6">
     <span class="helper-text">Select the Status of this new page.  Draft is selected by default.</span>
     <p>
     <label>
     <input name="nmenu_status" id="mstatus1" value="1" type="radio" class="with-gap" />
     <span>Published</span>
     </label>
     </p>
     <p>
     <label>
     <input name="nmenu_status" id="mstatus2" value="2" type="radio" class="with-gap" />
     <span>Hidden</span>
     </label>
     </p>
     <p>
     <label>
     <input name="nmenu_status" id="mstatus0" value="0" type="radio" class="with-gap" checked="checked" />
     <span>Draft</span>
     </label>
     </p>          
     </div>
     </div>
     </div>     
     <?php     
}

if(isset($_POST['get_children'])) {
     $child = $db->query("SELECT m_id, menu_name, menu_order FROM tbl_menu WHERE menu_parent_id = $_POST[parent] AND menu_status =! 9 ORDER BY menu_order");
     echo '<option value="0" selected>Place First</option>';
     while($c = $child->fetch(PDO::FETCH_ASSOC)) {
          echo '<option value="'. $c['menu_order'] .'">Place after: '. stripslashes($c['menu_name']) .'</option>';
     }
}

if(isset($_POST['save_new_page'])) {
     $_POST['menu_order'] = $_POST['menu_order'] + 1;
     $last = $db->prepare("INSERT INTO tbl_menu (menu_name, menu_link, menu_status, menu_order, menu_parent_id) VALUES ('$_POST[menu_name]', '$_POST[menu_link]', $_POST[menu_status], $_POST[menu_order], $_POST[menu_parent_id])");
     $last->execute();
     $lastid = $db->lastInsertId();
     $db->exec("INSERT INTO tbl_content (menu_id) VALUES ($lastid)");
     $order = $db->query("SELECT m_id FROM tbl_menu WHERE m_id != $lastid AND menu_parent_id = $_POST[menu_parent_id] AND menu_order >= $_POST[menu_order] ORDER BY menu_order ASC");
     while($o = $order->fetch(PDO::FETCH_ASSOC)) {
          $db->exec("UPDATE tbl_menu SET `menu_order` = `menu_order` + 1 WHERE `m_id` = $o[m_id]");
     }
     ?>
     <table class="striped" id="pages_table">
     <thead>
     <tr>
     <th>Page Name</th>
     <th>Parent</th>
     <th>Friendly Link</th>
     <th>Options</th>
     </tr>
     </thead>
     <tbody>
     <?php
     $list = $a->getPageListP();
     while($ls = $list->fetch(PDO::FETCH_ASSOC)) {
          ?>
          
          <tr>
          <td><?php echo stripslashes($ls['menu_name']) ?></td>
          <td><?php echo $a->getParent($ls['menu_parent_id']) ?></td>
          <td><?php echo $ls['menu_link'] ?></td>
          <td><?php echo $a->getMenuOptions($ls['m_id']) ?></td>
          </tr>
          
          <?php
          $clist = $a->getPageListC($ls['m_id']);
          if($clist->rowCount() > 0) {
               while($cs = $clist->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                    <td>-- <?php echo stripslashes($cs['menu_name']) ?></td>
                    <td><?php echo $a->getParent($cs['menu_parent_id']) ?></td>
                    <td><?php echo $cs['menu_link'] ?></td>
                    <td><?php echo $a->getMenuOptions($cs['m_id']) ?></td>
                    </tr>               
                    <?php
               }
          }
     }
     ?>
     </tbody>
     <tfoot>
     <th>Page Name</th>
     <th>Parent</th>
     <th>Last Update</th>
     <th>Options</th>
     </tfoot>
     </table>   
     <?php     
}

if(isset($_POST['add_new_menu'])) {
     $_POST['menu_name'] = addslashes($_POST['menu_name']);
     $chk = $db->query("SELECT m_id FROM tbl_menu WHERE (menu_name = '$_POST[menu_name]' OR menu_link = '$_POST[menu_link]') AND menu_status != 9");
     if($chk->rowCount() > 0) {
          echo 'There is already a menu on your site with this Name or Link. Please change and try again.';
          die;
     }
     $db->exec("INSERT INTO tbl_menu (menu_name, menu_link, menu_status, menu_order, menu_parent_id) VALUES ('$_POST[menu_name]', '$_POST[menu_link]', '$_POST[menu_status]', 0, '$_POST[menu_parent_id]')");
     echo 'Menu Added Successfully.';
}

if(isset($_POST['delete_image'])) {
     if($_POST['image_type'] == 'li') {
          $type = 'landing_image';
     }
     if($_POST['image_type'] == 'si') {
          $type = 'seo_image';
     }
     $db->exec("UPDATE tbl_content SET `$type` = '' WHERE p_id = $_POST[p_id]");
     echo 'Deleted';     
}

if(isset($_POST['delete_page'])) {
     $db->exec("UPDATE tbl_menu SET menu_status = 9 WHERE m_id = $_POST[m_id]");
     ?>
     <table class="striped" id="pages_table">
     <thead>
     <tr>
     <th>Page Name</th>
     <th>Parent</th>
     <th>Friendly Link</th>
     <th>Options</th>
     </tr>
     </thead>
     <tbody>
     <?php
     $list = $a->getPageListP();
     while($ls = $list->fetch(PDO::FETCH_ASSOC)) {
          ?>
          
          <tr>
          <td><?php echo stripslashes($ls['menu_name']) ?></td>
          <td><?php echo $a->getParent($ls['menu_parent_id']) ?></td>
          <td><?php echo $ls['menu_link'] ?></td>
          <td><?php echo $a->getMenuOptions($ls['m_id']) ?></td>
          </tr>
          
          <?php
          $clist = $a->getPageListC($ls['m_id']);
          if($clist->rowCount() > 0) {
               while($cs = $clist->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                    <td>-- <?php echo stripslashes($cs['menu_name']) ?></td>
                    <td><?php echo $a->getParent($cs['menu_parent_id']) ?></td>
                    <td><?php echo $cs['menu_link'] ?></td>
                    <td><?php echo $a->getMenuOptions($cs['m_id']) ?></td>
                    </tr>               
                    <?php
               }
          }
     }
     ?>
     </tbody>
     <tfoot>
     <th>Page Name</th>
     <th>Parent</th>
     <th>Last Update</th>
     <th>Options</th>
     </tfoot>
     </table>      
     <?php
}

if(isset($_POST['update_page'])) {
     $extarray = array('jpg', 'jpeg', 'png');
     $root = $g['doc_root'];
     if($_FILES['seo_image']['name'] > '') {
          $ext = pathinfo($_FILES['seo_image']['name'], PATHINFO_EXTENSION);
          $path = $root .'content/assets/seo_images/';
          if(!in_array(strtolower($ext), $extarray)) {
               echo 'The SEO Image you added does not have a valid file extension.  Only jpg (jpeg) and png images allowed. Changes not saved.';
               die;
          }
          $_POST['seo_image'] = date('Ymdhis') . rand(1, 4) .'.'. $ext;
          if(!move_uploaded_file($_FILES['seo_image']['tmp_name'], $path . $_POST['seo_image'])) {
               echo 'The SEO Image did not upload correctly, but the process continues...';
          }
     } else {
          unset($_POST['seo_image']);
     }
     if($_FILES['landing_image']['name'] > '') {
          $ext = pathinfo($_FILES['landing_image']['name'], PATHINFO_EXTENSION);
          $path = $root .'content/assets/landing_images/';          
          if(!in_array(strtolower($ext), $extarray)) {
               echo 'The Landing Image you added does not have a valid file extension.  Only jpg (jpeg) and png images allowed. Changes not saved.';
               die;
          }
          $_POST['landing_image'] = date('Ymdhis') . rand(1, 4) .'.'. $ext;
          if(!move_uploaded_file($_FILES['landing_image']['tmp_name'], $path . $_POST['landing_image'])) {
               echo 'The Landing Image did not upload correctly, but the process continues...';
          }          
     } else {
          unset($_POST['landing_image']);
     }
     $m_id = $_POST['menu_id'];
     $p_id = $_POST['page_id'];
     $menu_status = $_POST['menu_status'];
     $menu_link = $_POST['menu_link'];
     unset($_POST['update_page']);
     unset($_POST['menu_status']);
     unset($_POST['menu_link']);
     unset($_POST['menu_id']);
     unset($_POST['page_id']);
     $_POST['section_content'] = addslashes($_POST['section_content']);
     $_POST['page_title'] = addslashes($_POST['page_title']);
     $sql = "UPDATE tbl_content SET ";
     foreach($_POST AS $f => $v) {
          $sql .= "`$f` = '$v', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= " WHERE `p_id` = $p_id";
     $db->exec($sql);
     $db->exec("UPDATE tbl_menu SET menu_status = $menu_status, menu_link = '$menu_link' WHERE m_id = $m_id");
     echo 'Updated';         
}

if(isset($_POST['edit_user'])) {
     $user = $db->query("SELECT * FROM tbl_users WHERE user_id = '$_POST[user_id]'");
     $u = $user->fetch(PDO::FETCH_ASSOC);
     ?>
     <section class="section" style="padding: 10px;">
     <h4 class="modal-title">Edit User</h4>
     <p>Please change any of the following fields and click Update.</p>
     <div class="row">
     <form class="col s12">
     <input type="hidden" name="cur_user" id="cur_user" value="<?php echo $u['user_id'] ?>" />
     <div class="row">
     <div class="input-field col s6">
     <i class="material-icons prefix">account_circle</i>
     <input id="first_name" name="first_name" type="text" value="<?php echo $u['first_name'] ?>" required />
     <label for="first_name" class="active">First Name</label>
     </div>
     <div class="input-field col s6">
     <input id="last_name" name="last_name" type="text" value="<?php echo $u['last_name'] ?>" required />
     <label for="last_name" class="active">Last Name</label>
     </div>
     </div>
     
     <div class="row">
     <div class="input-field col s12">
     <i class="material-icons prefix">email</i>
     <input id="user_id" name="user_id" type="email" class="validate" value="<?php echo $u['user_id'] ?>" required />
     <label for="user_id" class="active">Email Address</label>
     <span class="helper-text" data-error="Incorrect email format." data-success="This appears valid!"></span>
     </div>
     </div>
     
     <div class="file-field input-field">
     <div class="btn yellow darken-2"><i class="material-icons left">add_a_photo</i>
     <span>User Avatar</span>
     <input type="file" name="user_avatar" id="user_avater" accept="image/jpg,image/png,image/gif" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="jpg/png/gif only!" />
     </div>
     </div>
     <div class="row">
     <div class="col s2 offset-s1">
     <img src="<?php echo $g['site_url'] ?>/content/assets/img/avatar/<?php echo $u['user_avatar'] ?>" width="105" class="circle responsive-img profile-image cyan" />
     </div>
     </div>
     
     <div class="row">
     <div class="input-field col s6">
     <select name="security_level" id="security_level" required="required">
     <?php
     $levels = $a->getSecLevels();
     while($lvl = $levels->fetch(PDO::FETCH_ASSOC)) {
          if($u['security_level'] == $lvl['s_id']) {
               echo '<option value="'. $lvl['s_id'] .'" selected="selected">'. $lvl['security_level'] .'</option>'."\n";               
          } else {
               echo '<option value="'. $lvl['s_id'] .'">'. $lvl['security_level'] .'</option>'."\n";
          }
     }
     ?>
     </select>
     <label>Security Level</label>
     </div>
     <div class="col s3">
     <p>When you are ready to save, click the Update button.</p>
     </div>
     <div class="col s3" style="text-align: right;">
     <a href="#!" onclick="updateAccount()" id="userupdate_btn" class="btn waves-effect waves-light indigo"><i class="material-icons left">save</i> Update</a><br />
     <div style="text-align: center;">
     <i class="fas fa-spinner fa-spin fa-lg" id="updatewait" style="display: none"></i>
     </div>
     </div>
     </div>
     </form>
     
     </section>
     <?php
}

if(isset($_POST['reset_pass'])) {
     ?>
     <section class="section" style="padding-left: 8px; padding-right: 8px;">
     <div class="row">
     <div class="col s12">
     <h4>Resetting a Password</h4>
     <p>To reset a password, you have two options.  You can create a new password by entering it below; or you can automatically generate a password by clicking "Auto Generate".
     In either case, the user will receive an email with the new password and a login link.</p>
     <div class="divider"></div>
     <div class="input-field col s6">
     <input placeholder="New Password" id="new_pass_1" name="new_pass_1" type="password" onkeyup="checkStrength()" class="validate" />
     <label for="new_pass_1">New Password</label>
     <span class="helper-text" id="new_pass_1_check"></span>
     </div>
     <div class="input-field col s6">
     <input placeholder="Enter Again" id="new_pass_2" name="new_pass_2" type="password" onkeyup="checkPasswords()" class="validate" />
     <label for="new_pass_2">Enter New Password Again</label>
     <span class="helper-text" id="new_pass_2_check"></span>     
     </div>
     </div>
     </div>
     <div class="divider"></div>
     <div class="row">
     <div class="col s12 offset-s10" style="display: none;" id="reset_button">
     <a class="btn yellow darken-1" style="margin-top: 10px; margin-bottom: 10px;" onclick="doResetPass('<?php echo $_POST['user_id'] ?>'); return false;" href="!#"><i class="material-icons left">thumb_up</i> Reset</a>
     </div>
     </div>
     <div class="divider"></div>
     <div class="row">
     <div class="col s12">
     <a class="btn blue darken-1" style="margin-top: 10px;" onclick="autoPass('<?php echo $_POST['user_id'] ?>'); return false;" href="!#"><i class="material-icons left">refresh</i> Auto Generate</a>     
     </div>
     </div>
     </section>
     <?php     
}

if(isset($_POST['do_reset_pass'])) {
     $password = $_POST['new_password'];
     $user = $_POST['user_id'];
     
     $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
     $newpass = hash('sha256', $password . $salt);
     
     $db->exec("UPDATE tbl_users SET password = '$newpass', salt = '$salt' WHERE user_id = '$user'");
     
     $a->sendEmail(2, $user, $password);
     
     echo '<h4 style="margin: 10px;">Password Reset Successfully!</h4><p style="margin: 10px;"><a href="#!" class="btn modal-close waves-effect waves-red">Close</a></p>';
}

if(isset($_POST['ban_user'])) {
     if($_POST['user_id'] == $_SESSION['user']['user_id']) {
          echo '2';
          die;
     }
     $db->exec("UPDATE tbl_users SET security_level = 0, account_status = 9 WHERE user_id = '$_POST[user_id]'");
     echo '1';
     die;
}

if(isset($_POST['add_user'])) {
     ?>
     <section class="section" style="padding: 10px;">
     <h4 class="modal-title">New User</h4>
     <p>Please fill out the following fields (some required) and click Add.  The user will receive an email with additional instructions.</p>
     <div class="row">
     <form class="col s12">
     
     <div class="row">
     <div class="input-field col s6">
     <i class="material-icons prefix">account_circle</i>
     <input id="first_name" name="first_name" type="text" required />
     <label for="first_name">First Name</label>
     </div>
     <div class="input-field col s6">
     <input id="last_name" name="last_name" type="text" required />
     <label for="last_name">Last Name</label>
     </div>
     </div>
     
     <div class="row">
     <div class="input-field col s12">
     <i class="material-icons prefix">email</i>
     <input id="user_id" name="user_id" type="email" class="validate" required />
     <label for="user_id">Email Address</label>
     <span class="helper-text" data-error="Incorrect email format." data-success="This appears valid!"></span>
     </div>
     </div>
     
     <div class="file-field input-field">
     <div class="btn yellow darken-2"><i class="material-icons left">add_a_photo</i>
     <span>User Avatar</span>
     <input type="file" name="user_avatar" id="user_avater" accept="image/jpg,image/png,image/gif" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="jpg/png/gif only!" />
     </div>
     </div>
     
     <div class="row">
     <div class="input-field col s6">
     <select name="security_level" id="security_level" required="required">
     <option value="" disabled selected>Choose...</option>
     <?php
     $levels = $a->getSecLevels();
     while($lvl = $levels->fetch(PDO::FETCH_ASSOC)) {
          echo '<option value="'. $lvl['s_id'] .'">'. $lvl['security_level'] .'</option>'."\n";
     }
     ?>
     </select>
     <label>Security Level</label>
     </div>
     <div class="col s3">
     <p>The new user account will be set to inactive.  An email will be sent to the email entered above with instructions for activation.</p>
     </div>
     <div class="col s3" style="text-align: right;">
     <a href="#!" onclick="saveAccount()" id="usersave_btn" class="btn waves-effect waves-light teal"><i class="material-icons left">save</i> Save</a><br />
     <div style="text-align: center;">
     <i class="fas fa-spinner fa-spin fa-lg" id="savewait" style="display: none"></i>
     </div>
     </div>
     </div>
     </form>
     
     </section>
     <?php
}

if(isset($_POST['save_account'])) {
     $res = '';
     unset($_POST['save_account']);
     if($_FILES['user_avatar']['name'] > '') {
          $savelocation = $g['doc_root'] .'content/assets/img/avatar/';
          $allowed = array("jpg", "jpeg", "png", "gif");
          $ext = pathinfo($_FILES['user_avatar']['name'], PATHINFO_EXTENSION);
          if(!in_array(strtolower($ext), $allowed)) {
               $res = '<div class="section" style="padding: 10px"><div class="red darken-1">You tried to use an invalid image type for the user avatar.  Remember, only jpeg(jpg), png and gif are allowed!</div></div><br />';
               $_POST['user_avatar'] = '';
          } else {
               $filename = date('ymdhis') . rand(0,4) .'.'. $ext;
               if(!move_uploaded_file($_FILES['user_avatar']['tmp_name'], $savelocation . $filename)) {
                    $res .= '<div class="red darken-1">The file was not uploaded.  An error occured.</div><br />';
                    $_POST['user_avatar'] = '';
               } else {
                    $_POST['user_avatar'] = $filename;
               }
          }
     } else {
          $res .= '<div class="section" style="padding: 10px"><div class="yellow darken-1">You did not include an avatar for the user.  This is alright, but it is recommended one be included.  You can add one by editing the user later.</div></div><br />';
          $_POST['user_avatar'] = '';
     }
     $chk = $db->query("SELECT user_id FROM tbl_users WHERE user_id = '$_POST[user_id]'");
     if($chk->rowCount() > 0) {
          $res = '<div class="section" style="padding: 10px"><div class="red darken-1">The Email Address you entered is already in use.  Please try again using a different email address!</div></div><br />';
          echo $res;
          die;
     } else {
          $password = $sec->randPassword(10);
          $_POST['salt'] = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
          $_POST['password'] = hash('sha256', $password . $_POST['salt']);
          $sql = "INSERT INTO tbl_users (";
          foreach($_POST AS $k => $v) {
               $sql .= "`$k`, ";
          }
          $sql = rtrim($sql, ", ");
          $sql .= ') VALUES (';
          foreach($_POST AS $k => $v) {
               $sql .= "'$v', ";
          }
          $sql = rtrim($sql, ", ");
          $sql .= ')';
          $db->exec($sql);
          $a->sendEmail(1, $_POST['user_id'], $password); 
          $res .= '<div class="section" style="padding: 10px"><div class="green darken-1">User added successfully!</div></div>';
        
     }
     echo $res;     
}

if(isset($_POST['update_account'])) {
     $res = '';
     unset($_POST['update_account']);
     if($_FILES['user_avatar']['name'] > '') {
          $savelocation = $g['doc_root'] .'content/assets/img/avatar/';
          $allowed = array("jpg", "jpeg", "png", "gif");
          $ext = pathinfo($_FILES['user_avatar']['name'], PATHINFO_EXTENSION);
          if(!in_array(strtolower($ext), $allowed)) {
               $res = '<div class="section" style="padding: 10px"><div class="red darken-1">You tried to use an invalid image type for the user avatar.  Remember, only jpeg(jpg), png and gif are allowed!</div></div><br />';
               unset($_POST['user_avatar']);
          } else {
               $filename = date('ymdhis') . rand(0,4) .'.'. $ext;
               if(!move_uploaded_file($_FILES['user_avatar']['tmp_name'], $savelocation . $filename)) {
                    $res .= '<div class="red darken-1">The file was not uploaded.  An error occured.</div><br />';
                    unset($_POST['user_avatar']);
               } else {
                    $_POST['user_avatar'] = $filename;
               }
          }
     } else {
          $res .= '<div class="section" style="padding: 10px"><div class="yellow darken-1">You did not include an avatar for the user.  This is alright.  The current avatar will remain.  You can add or update it by editing the user again.</div></div><br />';
          unset($_POST['user_avatar']);
     }
     if($_POST['cur_user'] != $_POST['user_id']) {
          $chk = $db->query("SELECT user_id FROM tbl_users WHERE user_id = '$_POST[user_id]'");
          if($chk->rowCount() > 0) {
               $res .= '<div class="section" style="padding: 10px"><div class="red darken-1">The Email Address you entered is already in use.  Please try again using a different email address!</div></div><br />';
               echo $res;
               die;
          }
     }
     $curuser = $db->quote($_POST['cur_user']);
     unset($_POST['cur_user']);
     $sql = "UPDATE tbl_users SET ";
     foreach($_POST AS $k => $v) {
          $v = $db->quote($v);
          $sql .= "`$k` = $v, ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= " WHERE user_id = $curuser";
     $db->exec($sql);
     $res .= '<div class="section" style="padding: 10px"><div class="green darken-1">User update successfully!</div></div>';  
     echo $res;      
}

if(isset($_POST['change_profile_value'])) {
     $db->exec("UPDATE tbl_users SET `$_POST[field]` = '$_POST[value]' WHERE user_id = '". $_SESSION['user']['user_id'] ."'");
     echo 'Updated';     
}

if(isset($_POST['update_value'])) {
	if($_POST['field'] == 'phone_1' || $_POST['field'] == 'phone_2' || $_POST['field'] == 'fax_1') {
		$_POST['value'] = preg_replace("/[^0-9]/", "", str_replace(" ","", $_POST['value']));
	}	
	$db->exec("UPDATE tbl_globals SET `$_POST[field]` = '$_POST[value]' WHERE g_id = 1");
     echo 'Updated';     
}

if(isset($_POST['update_sermon_config'])) {
     $db->exec("UPDATE tbl_sermons_settings SET `$_POST[field]` = '$_POST[value]' WHERE s_id = 1");
     echo 'Updated';     
}

if(isset($_POST['view_preacher'])) {
     $prh = $db->query("SELECT * FROM tbl_sermons_preachers WHERE pr_id = $_POST[pr_id]");
     $ph = $prh->fetch(PDO::FETCH_ASSOC);
     ?>
     <div class="input-field col s6">     
     <select name="title" id="title" onchange="updatePreacher(<?php echo $ph['pr_id'] ?>, this.id, this.value)">
     <option <?php if($ph['title'] == 'Mr.') { echo 'selected="selected"';} ?> value="Mr.">Mr.</option>
     <option <?php if($ph['title'] == 'Rev.') { echo 'selected="selected"';} ?> value="Rev.">Rev.</option>
     <option <?php if($ph['title'] == 'Dr.') { echo 'selected="selected"';} ?> value="Dr.">Dr.</option>
     <option <?php if($ph['title'] == 'Rev. Dr.') { echo 'selected="selected"';} ?> value="Rev. Dr.">Rev. Dr.</option>
     <option <?php if($ph['title'] == 'Fr.') { echo 'selected="selected"';} ?> value="Fr.">Fr.</option>
     </select>
     <label>Title</label>     
     </div>
     <div class="input-field col s12">
     <input type="text" name="first_name" id="first_name" value="<?php echo $ph['first_name'] ?>" onblur="updatePreacher(<?php echo $ph['pr_id'] ?>, this.id, this.value)" />
     <label class="active" for="first_name">First Name</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="last_name" id="last_name" value="<?php echo $ph['last_name'] ?>" onblur="updatePreacher(<?php echo $ph['pr_id'] ?>, this.id, this.value)" />
     <label class="active" for="last_name">Last Name</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="preacher_location" id="preacher_location" value="<?php echo $ph['preacher_location'] ?>" onblur="updatePreacher(<?php echo $ph['pr_id'] ?>, this.field, this.value)" />
     <label class="active" for="preacher_location">Serving Location</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="preacher_postion" id="preacher_position" value="<?php echo $ph['preacher_position'] ?>" onblur="updatePreacher(<?php echo $ph['pr_id'] ?>, this.field, this.value)" />
     <label class="active" for="preacher_position">Current Position at Location</label>
     </div>
     <div class="input-field col s12">
     <input type="email" name="preacher_email" id="preacher_email" value="<?php echo $ph['preacher_email'] ?>" onblur="updatePreacher(<?php echo $ph['pr_id'] ?>, this.field, this.value)" />
     <label class="active" for="preacher_email">Email Address</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="preacher_phone" id="preacher_phone" value="<?php echo $ph['preacher_phone'] ?>" onblur="updatePreacher(<?php echo $ph['pr_id'] ?>, this.field, this.value)" />
     <label class="active" for="preacher_phone">Phone Number (numbers only)</label>
     </div>
     <div class="col s12">
     <a href="#!" class="waves-effect waves-light btn red" onclick="deletePreacher(<?php echo $ph['pr_id'] ?>)"><i class="material-icons left">delete</i>Delete</a>
     </div>
     <?php
}

if(isset($_POST['new_preacher'])) {
     ?>
     <div class="input-field col s6">     
     <select name="title" id="ntitle">
     <option value="" selected disabled>Select</option>
     <option value="Mr.">Mr.</option>
     <option value="Rev.">Rev.</option>
     <option value="Dr.">Dr.</option>
     <option value="Rev. Dr.">Rev. Dr.</option>
     <option value="Fr.">Fr.</option>
     </select>
     <label>Title</label>     
     </div>
     <div class="input-field col s12">
     <input type="text" name="first_name" id="nfirst_name" />
     <label class="active" for="nfirst_name">First Name</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="last_name" id="nlast_name" />
     <label class="active" for="nlast_name">Last Name</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="preacher_location" id="npreacher_location" />
     <label class="active" for="npreacher_location">Serving Location</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="preacher_postion" id="npreacher_position" />
     <label class="active" for="npreacher_position">Current Position at Location</label>
     </div>
     <div class="input-field col s12">
     <input type="email" name="preacher_email" id="npreacher_email" />
     <label class="active" for="npreacher_email">Email Address</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="preacher_phone" id="npreacher_phone" />
     <label class="active" for="npreacher_phone">Phone Number (numbers only)</label>
     </div>
     <div class="col s12">
     <a href="#!" class="waves-effect waves-light btn teal" onclick="addPreacher()"><i class="material-icons left">save</i>Add</a>
     </div>

     <?php     
}

if(isset($_POST['add_preacher'])) {
     unset($_POST['add_preacher']);
     $sql = "INSERT INTO tbl_sermons_preachers (";
     foreach($_POST AS $f => $v) {
          $sql .= "`$f`, ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ") VALUES (";
     foreach($_POST AS $f => $v) {
          if($f == 'preacher_phone') {
               $v = preg_replace("/[^0-9]/", "", $v);
          }
          $sql .= "'$v', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ")";
     $db->exec($sql);
     echo 'Preacher Added Successfully.';
}

if(isset($_POST['update_preacher'])) {
     $db->exec("UPDATE tbl_sermons_preachers SET `$_POST[f]` = '$_POST[v]' WHERE pr_id = $_POST[pr_id]");
     echo 'Updated';     
}

if(isset($_POST['delete_preacher'])) {
     $db->exec("UPDATE tbl_sermons_preachers SET preacher_status = 0 WHERE pr_id = $_POST[pr_id]");
     echo 'Updated';     
}

if(isset($_POST['new_season'])) {
     ?>
     <div class="input-field col s12">
     <input type="text" name="nseason_name" id="nseason_name" placeholder="Season Name" />
     <label for="nseason_name" class="active">Season Name</label>
     </div>
     <div class="input-field col s12">
     <select name="nseason_color" id="nseason_color">
     <option value="" selected disabled>Select</option>
     <option class="black" value="black">Black</option>
     <option class="blue" value="blue">Blue</option>
     <option class="yellow accent-4" value="yellow accent-4">Gold</option>
     <option class="green" value="green">Green</option>
     <option class="pink" value="pink">Pink</option>
     <option class="purple" value="purple">Purple</option>
     <option class="red" value="red">Red</option>
     <option class="pink accent-1" value="pink accent-1">Rose</option>
     <option class="grey lighten-5" value="grey lighten-5">White</option>
     </select>
     <label>Season Color</label>
     </div>
     <a href="#!" class="waves-effect waves-light btn teal" onclick="addSeason()"><i class="material-icons">save</i></a>     
     <?php
}

if(isset($_POST['add_season'])) {
     $db->exec("INSERT INTO tbl_sermons_seasons (season_name, season_order, season_status, season_color) VALUES ('$_POST[season_name]', 0, 1, '$_POST[season_color]')");
     echo 'Season Added';     
}

if(isset($_POST['edit_season'])) {
     $sea = $db->query("SELECT * FROM tbl_sermons_seasons WHERE se_id = $_POST[se_id]");
     $se = $sea->fetch(PDO::FETCH_ASSOC);
     ?>
     <div class="input-field col s12">
     <input type="text" name="season_name" id="season_name" placeholder="Season Name" value="<?php echo $se['season_name'] ?>" onblur="updateSeason(<?php echo $se['se_id'] ?>, this.id, this.value)" />
     </div>
     <div class="input-field col s12">
     <select name="season_color" id="season_color" onchange="updateSeason(<?php echo $se['se_id'] ?>, this.id, this.value)">
     <option <?php if($se['season_color'] == 'black') { echo 'selected="selected"';} ?> class="black" value="black">Black</option>
     <option <?php if($se['season_color'] == 'blue') { echo 'selected="selected"';} ?> class="blue" value="blue">Blue</option>
     <option <?php if($se['season_color'] == 'yellow accent-4') { echo 'selected="selected"';} ?> class="yellow accent-4" value="yellow accent-4">Gold</option>
     <option <?php if($se['season_color'] == 'green') { echo 'selected="selected"';} ?> class="green" value="green">Green</option>
     <option <?php if($se['season_color'] == 'pink') { echo 'selected="selected"';} ?> class="pink" value="pink">Pink</option>
     <option <?php if($se['season_color'] == 'purple') { echo 'selected="selected"';} ?> class="purple" value="purple">Purple</option>
     <option <?php if($se['season_color'] == 'red') { echo 'selected="selected"';} ?> class="red" value="red">Red</option>
     <option <?php if($se['season_color'] == 'pink accent-1') { echo 'selected="selected"';} ?> class="pink accent-1" value="pink accent-1">Rose</option>
     <option <?php if($se['season_color'] == 'grey lighten-5') { echo 'selected="selected"';} ?> class="grey lighten-5">White</option>
     </select>
     <label>Season Color</label>
     </div>
     <a href="#!" class="waves-effect waves-light btn red" onclick="removeSeason(<?php echo $se['se_id'] ?>)"><i class="material-icons">delete</i></a>    
     <a href="#!" class="waves-effect waves-light btn teal" onclick="newSeason()"><i class="material-icons">clear_all</i></a>
     <?php
}

if(isset($_POST['update_season'])) {
     $db->exec("UPDATE tbl_sermons_seasons SET `$_POST[f]` = '$_POST[v]' WHERE se_id = $_POST[se_id]");
     echo 'Updated';     
}

if(isset($_POST['remove_season'])) {
     $db->exec("UPDATE tbl_sermons_seasons SET season_status = 0 WHERE se_id = $_POST[se_id]");
     echo 'Removed';          
}

if(isset($_POST['season'])) {
     $i = 0;
     foreach($_POST['season'] AS $value) {
          $db->exec("UPDATE tbl_sermons_seasons SET season_order = $i WHERE se_id = $value");
          $i++;
     }
     echo 'Updated';          
}

if(isset($_POST['new_series'])) {
     ?>
     <div class="input-field col s12">
     <input type="text" name="nseries_name" id="nseries_name" />
     <label for="nseries_name" class="active">New Series Name</label>
     </div>
     <a href="#!" class="waves-effect waves-light btn teal" onclick="addSeries()"><i class="material-icons">save</i></a>     
     
     <?php
}

if(isset($_POST['add_series'])) {
     $db->exec("INSERT INTO tbl_sermons_series (series_name, series_status) VALUES ('$_POST[series_name]', 1)");
     echo 'Added';     
}

if(isset($_POST['edit_series'])) {
     $ser = $db->query("SELECT * FROM tbl_sermons_series WHERE se_id = $_POST[se_id]");
     $ss = $ser->fetch(PDO::FETCH_ASSOC);
     ?>
     <div class="input-field col s12">
     <input type="text" name="series_name" id="series_name" value="<?php echo $ss['series_name'] ?>" onblur="updateSeries(<?php echo $ss['se_id'] ?>, this.id, this.value)" />
     <label for="nseries_name" class="active">Series Name</label>
     </div>
     <a href="#!" class="waves-effect waves-light btn red" onclick="removeSeries(<?php echo $ss['se_id'] ?>)"><i class="material-icons">delete</i></a>    
     <a href="#!" class="waves-effect waves-light btn teal" onclick="newSeries()"><i class="material-icons">clear_all</i></a>
     
     <?php
}

if(isset($_POST['update_series'])) {
     $db->exec("UPDATE tbl_sermons_series SET `$_POST[f]` = '$_POST[v]' WHERE se_id = $_POST[se_id]");
     echo 'Updated';     
}

if(isset($_POST['remove_series'])) {
     $db->exec("UPDATE tbl_sermons_series SET series_status = 0 WHERE se_id = $_POST[se_id]");
     echo 'Removed';          
}

if(isset($_POST['new_sermon'])) {
     ?>
     <div class="row">
     <form id="newsermonform">
     <input type="hidden" name="newSermon" id="newSermon" value="1" />
     <div class="col s12 m4">
     <div class="input-field col s12">
     <input type="text" name="sermon_title" id="sermon_title" />
     <label for="nsermon_title">Sermon Title</label>
     </div>
     <div class="input-field col s12">
     <input type="text" name="sermon_topic" id="sermon_topic" />
     <label for="sermon_topic">Sermon Topic</label>
     </div>
     <div class="input-field col s6">
     <input type="text" class="datepicker" name="sermon_date" id="sermon_date" />
     <label for="sermon_date">Date Preached</label>
     </div>
     
     </div>
     <div class="col s12 m4">
     
     </div>
     <div class="col s12 m4">
     
     </div>
     </form>
     </div>
     
     <?php
}

if(isset($_POST['edit_sermon'])) {
     ?>
     
     
     <?php
}

if(isset($_POST['change_featured'])) {
     $feat = $db->query("SELECT sermon_featured FROM tbl_sermons WHERE se_id = $_POST[se_id]");
     $f = $feat->fetch(PDO::FETCH_ASSOC);
     if($f['sermon_featured'] == 0) {
          $db->exec("UPDATE tbl_sermons SET sermon_featured = 1 WHERE se_id = $_POST[se_id]");
          echo '1';
     }
     if($f['sermon_featured'] == 1){
          $db->exec("UPDATE tbl_sermons SET sermon_featured = 0 WHERE se_id = $_POST[se_id]");
          echo '0';
     }
     echo 'Updated';     
}

if(isset($_POST['hide_sermon'])) {
     $db->exec("UPDATE tbl_sermons SET sermon_status = 0 WHERE se_id = $_POST[se_id]");
     echo 'Updated';     
}

if(isset($_POST['show_sermon'])) {
     $db->exec("UPDATE tbl_sermons SET sermon_status = 1 WHERE se_id = $_POST[se_id]");
     echo 'Updated';     
}

if(isset($_POST['delete_sermon'])) {
     $db->exec("UPDATE tbl_sermons SET sermon_status = 9 WHERE se_id = $_POST[se_id]");
     echo 'Deleted';     
}

if(isset($_POST['edit_block'])) {
	?>
	<h4 class="modal-title" id="block_sample">Block Editor</h4>
     
	<?php
     $block = $b->getBlockContent($_POST['block']);
     $bl = $block->fetch(PDO::FETCH_ASSOC);
	switch($bl['block_area']) {
		case 'fl':
			?>
			<h6>Left Footer Block</h6>
			<div class="row">
			<input type="hidden" id="blockarea" value="fl" />
			<p>
               <label>
			<input type="checkbox" id="company_onlyl" onchange="showCompany('#company_onlyl')" <?php if($bl['block_content'] == 'company') { echo 'checked="checked"';} ?> />
			<span>Check to populate this block with your company information (overrides what you enter below).</span>
               </label>
			</p>
			<div id="summerblock"><?php echo $bl['block_content'] ?></div>
			</div>
			<?php
			break;			
		case 'fm':
			?>
			<h6>Center Footer Block</h6>
			<div class="row">
			<input type="hidden" id="blockarea" value="fm" />
			<p>
               <label>
			<input type="checkbox" id="company_onlym" onchange="showCompany('#company_onlym')" <?php if($bl['block_content'] == 'company') { echo 'checked="checked"';} ?> />
			<span>Check to populate this block with your company information (overrides what you enter below).</span>
               </label>
			</p>
			<div id="summerblock"><?php echo $bl['block_content'] ?></div>
			</div>
			<?php
			break;			
		case 'fr':
			?>
			<h6>Right Footer Block</h6>
			<div class="row">
			<input type="hidden" id="blockarea" value="fr" />
			<p>
               <label>
			<input type="checkbox" id="company_onlyr" onchange="showCompany('#company_onlyr')" <?php if($bl['block_content'] == 'company') { echo 'checked="checked"';} ?> />
			<span>Check to populate this block with your company information (overrides what you enter below).</span>
               </label>
			</p>
			<div id="summerblock"><?php echo $bl['block_content'] ?></div>
			</div>
			<?php
			break;
		case 'nav':
               ?>
               <h6>Navigation Bar</h6>
               <div class="divider"></div>
               <div class="row">
               <input type="hidden" id="blockarea" value="nav" />
               <div class="input-field col s12 m4 l4">
               <input type="number" min="35" max="175" name="navbar_height" id="nabvar_height" value="<?php echo $b->getBlockValue('nav') ?>" onchange="updateBlock('nav', this.value)" />
               <label for="navbar_height" class="active">Navigation Bar Height (in pixels)</label>
               </div>
               <div class="input-field col s12 m4 l4">
               <input type="number" min="10" max="120" name="title_font_size" id="title_font_size" value="<?php echo $b->getBlockValue('navt') ?>" onchange="updateBlock('navt', this.value)" />
               <label for="title_font_size" class="active">Title Font Size (in pixels)</label>
               </div>
               <div class="col s12 m4 l4">
               <p>
               <label>
               <input type="radio" name="navbar_type" id="type_fixed" value="f" <?php if($b->getBlockValue('navf') == 'f') { echo 'checked="checked"'; } ?> onchange="updateBlock('navf', 'f')" />
               <span>Fixed Nabvar</span>
               </label>
               </p>
               <p>
               <label>
               <input type="radio" name="navbar_type" id="type_fluid" value="l" <?php if($b->getBlockValue('navf') == 'l') { echo 'checked="checked"'; } ?> onchange="updateBlock('navf', 'l')" />
               <span>Fluid Nabvar (default)</span>
               </label>               
               </p>
               </div>
               </div>
               <div class="row">
               <div class="col s12 m4 l4">
               <p>
               <label>
               <input type="radio" name="menu_align" id="align_left" value="l" <?php if($b->getBlockValue('navm') == 'l') { echo 'checked="checked"'; } ?> onchange="updateBlock('navm', 'l')" />
               <span>Menu Alignment Left</span>
               </label>
               </p>
               <p>
               <label>
               <input type="radio" name="menu_align" id="align_right" value="l" <?php if($b->getBlockValue('navm') == 'r') { echo 'checked="checked"'; } ?> onchange="updateBlock('navm', 'r')" />
               <span>Menu Alignment Right</span>
               </label>               
               </p>
               </div>
               <div class="col s12 m4 l4">
               <label>Navbar Background Color</label>
               <select name="nav_color" id="nav_color" class="browser-default" onchange="updateBlock('navc', this.value)">
               <?php echo $b->getTemplateColors($b->getBlockValue('navc')) ?>
               
               </select><br />
               <label>Shade Adjustment</label>
               <select name="nav_shade" id="nav_shade" class="browser-default" onchange="updateBlock('navcc', this.value)">
               <?php echo $b->getTemplateShades($b->getBlockValue('navcc')) ?>
               
               </select>
               </div>
                              
               </div>
               <?php
			break;
		case 'cnt':
               echo 'There is currently no editable items in the Content Block';
			break;
		default:
               echo 'No Block Selected';
			break;
	}
}

if(isset($_POST['update_block'])) {
     $db->exec("UPDATE tbl_blocks SET `block_content` = '$_POST[value]' WHERE block_area = '$_POST[field]'");
     echo 'Updated';     
}

if(isset($_POST['save_block_content'])) {
	$blockcontent = $db->quote($_POST['block_content']);
	$db->exec("UPDATE tbl_blocks SET block_content = $blockcontent WHERE block_area = '$_POST[block_area]'");
     echo 'Updated';     
}

if(isset($_POST['save_block_company'])) {
	$db->exec("UPDATE tbl_blocks SET block_content = 'company' WHERE block_area = '$_POST[block_area]'");
     echo 'Updated';     
}

if(isset($_POST['add_download'])) {
     ?>
     <div class="row">
     <div class="col s12">
     <div class="row">
     <div class="input-field col s4">
     <input type="text" name="download_name" id="download_name" required="" />
     <label for="download_name">Resourse Title</label>
     <span class="helper-text">Required: What is this download resource called?</span>
     </div>
     <div class="input-field col s4">
     <input type="text" name="download_date_added" class="datepicker" id="download_date_added" required="" />
     <label for="download_date_added">Date of Resource</label>
     <span class="helper-text">Enter a date.  If left blank, today's date will be used.</span>     
     </div>
     <div class="file-field input-field col s4">
     <div class="btn">
     <span>Resource</span>
     <input type="file" name="download_filename" id="download_filename" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="Click to browse" />
     <span class="helper-text">Many file types may be used.  One file per resource.</span>
     </div>
     </div>
     </div>
     <div class="row">
     <div class="col s6">
     <span class="helper-text">Download Type</span>
     <p>
     <label>
     <input type="radio" name="download_action" id="action1" value="1" checked="checked" />
     <span>Force Download (recommended)</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="download_action" id="action2" value="2" />
     <span>Allow Browser Viewing (if available)</span>
     </label>
     </p>
     </div>
     <div class="input-field col s6">
     <input type="text" name="download_password" id="download_password" />
     <label for="download_password">Optional Password</label>
     <span class="helper-text">If you want to limit this resource to only authenticated users, enter a password here.</span>
     </div>
     </div>
     <div class="row">
     <div class="input-field col s6">
     <select name="download_page_id" id="download_page_id">
     <option value="0" selected disabled>Choose</option>
     <?php echo $d->getPages() ?>
     
     </select>
     <label>Page to add Resource</label>
     <span class="helper-text">For this resource to be accessible, you must select which page to display it on.  Only pages with the Downloads Plugin enabled show in this list.</span>
     </div>
     <div class="input-field col s6">
     <select name="download_security_level" id="download_security_level">
     <option value="0" disabled selected>Choose</option>
     <?php echo $d->getSecurity() ?>
     
     </select>
     <label>Optional Security Level Limit</label>
     <span class="helper-text">You can limit the viewing of this download by security level.  Do not select if you want it viewable by all users.</span>
     </div>
     </div>
     </div>
     </div>
     
     <?php
}

if(isset($_POST['save_resource'])) {
     unset($_POST['save_resource']);
     $root = $g['doc_root'] .'content/assets/uploads/';
     $allowed = array('docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf', 'txt', 'rtf', 'zip', 'rar', 'tar', 'gz', 'mp3', 'ogg', 'wav', 'wma', 'aiff', 'aac', 'm4a', 'avi', 'flv', 'wmv', 'mov', 'mp4', 'mpg', 'jpg', 'jpeg', 'png', 'gif', 'tiff', 'tif');
     if($_FILES['download_filename']['name'] == '') {
          echo '<div class="red white-text" style="padding: 10px; border-radius: 10px;">You did not include a file!  You cannot create a downloadable resource without a file, silly.  Please include a Resource.</div>';
          die;
     }
     $ext = strtolower(pathinfo($_FILES['download_filename']['name'], PATHINFO_EXTENSION));
     if(!in_array($ext, $allowed)) {
          echo '<div class="red white-text" style="padding: 10px; border-radius: 10px;">Sorry, but the file you tried to upload is not accepted as a safe or widely used file.  Generally, no executable files, system files, web files (html, php, asp, etc.) or oddly-named
           files are allowed.  Most document files, image files, audio files and video files are accepted.  If you think the file you are trying to upload should be allowed, 
           submit a support request or contact the Administrator.</div>';
          die;
     }
     $filename = date('Ymdhis') . rand(0, 4) .'.'. $ext;
     $_POST['download_type'] = strtoupper($ext);
     $_POST['download_filename'] = $filename;
     if(move_uploaded_file($_FILES['download_filename']['tmp_name'], $root . $filename)) {
          if($_POST['download_date_added'] == '') {
               $_POST['download_date_added'] = date('Y-m-d h:i:s');
          } else {
               $_POST['download_date_added'] = date('Y-m-d h:i:s', strtotime($_POST['download_date_added']));
          }
          if($_POST['download_name'] == '') {
               echo '<div class="red white-text" style="padding: 10px; border-radius: 10px;">Nope, you need to include the Resource Name!  Now you gotta start all over again :-(.</div>';
               die;
          }
          if($_POST['download_page_id'] == 0) {
               echo '<div class="red white-text" style="padding: 10px; border-radius: 10px;">Eh, you failed to select a Page to which you would like this Resource shown.  Try again, and be SURE to select a page under the Page to Add Resource option.</div>';
               die;
          }
          $_POST['download_added_by'] = $_SESSION['user']['user_id'];
          $_POST['download_count'] = 0;
          $_POST['download_status'] = 1;
          $_POST['download_date_last'] = date('Y-m-d h:i:s');
          $sql = "INSERT INTO tbl_downloads (";
          foreach($_POST AS $k => $v) {
               $sql .= "`$k`, ";
          }
          $sql = rtrim($sql, ", ");
          $sql .= ") VALUES (";
          foreach($_POST AS $k => $v) {
               if($k == 'download_name') {
                    $v = addslashes($v);
               }
               $sql .= "'$v', ";
          }
          $sql = rtrim($sql, ", ");
          $sql .= ")";
          $db->exec($sql);
          echo '<div class="green white-text" style="padding: 10px; border-radius: 10px;">Great news!  Your downloadable resource has been added and is available for use!</div>';
     }
}

// Calendar Scripts
if(isset($_POST['add_calendar'])) {
     ?>
     <div class="row">
     <div class="col s12">
     <div class="input-field col s12">
     <input id="event_calendar_name" id="event_calendar_name" type="text" />
     <label for="event_calendar_name">Calendar Name</label>
     </div>
     
     <div class="row">
     <div class="col s3">
     <span class="form-text">Calendar Status</span>
     <p>
     <label>
     <input type="radio" name="event_calendar_status" id="e_status_1" value="1" />
     <span>Enabled</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_calendar_status" id="e_status_0" value="0" />
     <span>Disabled</span>
     </label>     
     </p>
     </div>
     
     <div class="col s3">
     <span class="form-text">Calendar Layout</span>
     <p>
     <label>
     <input type="radio" name="event_calendar_layout" id="e_layout_1" value="1" />
     <span>Grid Format</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_calendar_layout" id="e_layout_2" value="2" />
     <span>List Format</span>
     </label>
     </p>
     </div>
     
     <div class="col s3">
     <span class="form-text">Event Bookings</span>
     <p>
     <label class="tooltipped" data-position="top" data-tooltip="Can be disabled per event" >
     <input type="radio" name="allow_bookings" id="e_bookings_1" value="1" />
     <span>Allowed</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="allow_bookings" id="e_bookings_0" value="0" />
     <span>Not Allowed</span>
     </label>
     </p>     
     </div>
     
     <div class="col s3">
     <span class="form-text">Event Payments</span>
     <p>
     <label class="tooltipped" data-position="top" data-tooltip="Can be disabled per event">
     <input type="radio" name="allow_payments" id="e_payments_1" value="1" />
     <span>Allowed</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="allow_payments" id="e_payments_0" value="0" />
     <span>Not Allowed</span>
     </label>
     </p>      
     </div>
     </div>
     
     <div class="input-field col s12">
     <input type="email" name="paypal_payment_email" id="paypal_payment_email" />
     <label for="paypal_payment_email">Paypal Payments Email Address</label>
     </div>
     
     <div class="input-field col s12">
     <textarea name="paypal_payment_api" id="paypal_payment_api" class="materialize-textarea"></textarea>
     <label for="paypal_payment_api">Paypal Payments Button HTML Code</label>
     <span class="form-text text-muted">This is the HTML code from Paypal that creates the Payment button.  Log into your paypal account and in the payment settings screen, create
     a new button.  After customizing it as you would like, copy the generated HTML code and paste it here.</span>
     </div>
     
     <div class="input-field col s12">
     <textarea name="google_maps_api" id="google_maps_api" class="materialize-textarea"></textarea>
     <label for="google_maps_api">Google Mapes API</label>
     <span class="form-text text-muted">If you want the Location of events to appear on an interactive Google map, enter the necessary API code above.</span>
     </div>
     
     </div>
     </div>
     
     <?php
}

if(isset($_POST['save_calendar'])) {
     unset($_POST['save_calendar']);
     $sql = "INSERT INTO tbl_events_calendar_settings (";
     foreach($_POST AS $k => $v) {
          $sql .= "`$k`, ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ") VALUES (";
     foreach($_POST AS $k => $v) {
          $sql .= "'". addslashes($v) ."', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ")";
     $db->exec($sql);
     echo 'Calendar Added';
}

if(isset($_POST['view_cal_settings'])) {
     $cal = $db->query("SELECT * FROM tbl_events_calendar_settings WHERE es_id = $_POST[es_id]");
     $ca = $cal->fetch(PDO::FETCH_ASSOC);
     ?>
     <br />
     <hr />
     <div class="row">
     <div class="col s12">
     <div class="input-field col s12">
     <input id="event_calendar_name" id="event_calendar_name" type="text" value="<?php echo $ca['event_calendar_name'] ?>" onblur="updateCalSetting(<?php echo $ca['es_id'] ?>, this.id, this.value)" />
     <label for="event_calendar_name" class="active">Calendar Name</label>
     </div>
     
     <div class="row">
     <div class="col s3">
     <span class="form-text">Calendar Status</span>
     <p>
     <label>
     <input type="radio" name="event_calendar_status" id="e_status_1" value="1" <?php if($ca['event_calendar_status'] == 1) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'event_calendar_status', this.value)" />
     <span>Enabled</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_calendar_status" id="e_status_0" value="0" <?php if($ca['event_calendar_status'] == 0) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'event_calendar_status', this.value)" />
     <span>Disabled</span>
     </label>     
     </p>
     </div>
     
     <div class="col s3">
     <span class="form-text">Calendar Layout</span>
     <p>
     <label>
     <input type="radio" name="event_calendar_layout" id="e_layout_1" value="1" <?php if($ca['event_calendar_layout'] == 1) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'event_calendar_layout', this.value)" />
     <span>Grid Format</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_calendar_layout" id="e_layout_2" value="2" <?php if($ca['event_calendar_layout'] == 2) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'event_calendar_layout', this.value)" />
     <span>List Format</span>
     </label>
     </p>
     </div>
     
     <div class="col s3">
     <span class="form-text">Event Bookings</span>
     <p>
     <label class="tooltipped" data-position="top" data-tooltip="Can be disabled per event" >
     <input type="radio" name="allow_bookings" id="e_bookings_1" value="1" <?php if($ca['allow_bookings'] == 1) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'allow_bookings', this.value)" />
     <span>Allowed</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="allow_bookings" id="e_bookings_0" value="0" <?php if($ca['allow_bookings'] == 0) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'allow_bookings', this.value)" />
     <span>Not Allowed</span>
     </label>
     </p>     
     </div>
     
     <div class="col s3">
     <span class="form-text">Event Payments</span>
     <p>
     <label class="tooltipped" data-position="top" data-tooltip="Can be disabled per event">
     <input type="radio" name="allow_payments" id="e_payments_1" value="1" <?php if($ca['allow_payments'] == 1) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'allow_payments', this.value)" />
     <span>Allowed</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="allow_payments" id="e_payments_0" value="0" <?php if($ca['allow_payments'] == 0) { echo 'checked="checked"';} ?> onclick="updateCalSetting(<?php echo $ca['es_id'] ?>, 'allow_payments', this.value)" />
     <span>Not Allowed</span>
     </label>
     </p>      
     </div>
     </div>
     <hr />
     
     <div class="input-field col s12">
     <input type="email" name="paypal_payment_email" id="paypal_payment_email" value="<?php echo $ca['paypal_payment_email'] ?>" onblur="updateCalSetting(<?php echo $ca['es_id'] ?>, this.id, this.value)" />
     <label for="paypal_payment_email" class="active">Paypal Payments Email Address</label>
     </div>
     
     <div class="input-field col s12">
     <textarea name="paypal_payment_api" id="paypal_payment_api" class="materialize-textarea" onblur="updateCalSetting(<?php echo $ca['es_id'] ?>, this.id, this.value)"><?php echo htmlentities($ca['paypal_payment_api']) ?></textarea>
     <label for="paypal_payment_api" class="active">Paypal Payments Button HTML Code</label>
     <span class="form-text text-muted">This is the HTML code from Paypal that creates the Payment button.  Log into your paypal account and in the payment settings screen, create
     a new button.  After customizing it as you would like, copy the generated HTML code and paste it here.</span>
     </div>
     
     <div class="input-field col s12">
     <textarea name="google_maps_api" id="google_maps_api" class="materialize-textarea" onblur="updateCalSetting(<?php echo $ca['es_id'] ?>, this.id, this.value)"><?php echo htmlentities($ca['google_maps_api']) ?></textarea>
     <label for="google_maps_api" class="active">Google Mapes API</label>
     <span class="form-text text-muted">If you want the Location of events to appear on an interactive Google map, enter the necessary API code above.</span>
     </div>
     <div class="col s12 right-align">
     <a href="#!" onclick="deleteCalendar(<?php echo $ca['es_id'] ?>)" class="waves-effect waves-light btn-small red" title="Delete Calendar"><i class="material-icons">delete_forever</i></a>
     </div>
     </div>
     </div>
     
     <?php
}

if(isset($_POST['update_cal_setting'])) {
     $db->exec("UPDATE tbl_events_calendar_settings SET `$_POST[f]` = '$_POST[v]' WHERE es_id = $_POST[es_id]");
     echo 'Field Updated Successfully';
}

if(isset($_POST['delete_calendar'])) {
     $db->exec("UPDATE tbl_events_calendar SET event_status = 9 WHERE event_calendar_id = $_POST[es_id]");
     $db->exec("DELETE FROM tbl_events_calendar_settings WHERE es_id = $_POST[es_id]");
     echo 'Calendar Deleted.';
}

if(isset($_POST['edit_category'])) {
     $ect = $db->query("SELECT * FROM tbl_events_calendar_categories WHERE ec_id = $_POST[ec_id]");
     $ec = $ect->fetch(PDO::FETCH_ASSOC);
     ?>
     <input type="hidden" name="sec_id" id="sec_id" value="<?php echo $ec['ec_id'] ?>" />
     <div class="row">
     <div class="col s12 m12 right-align">
     <a title="Delete Category" class="waves-effect waves-light btn-small red" href="#!" onclick="deleteCategory('<?php echo $ec['ec_id'] ?>')"><i class="material-icons">delete_forever</i></a>
     </div>
     </div>
     <div class="row">
     <div class="input-field col s12 m6">
     <input type="text" name="sevent_category_name" id="sevent_category_name" value="<?php echo $ec['event_category_name'] ?>" />
     <label for="sevent_category_name" class="active">Category Name</label>
     </div>
     <div class="col s6 m3">
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="red" <?php if($ec['event_category_color'] == 'red') { echo 'checked="checked"';} ?> />
     <span class="red-text">Red</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="pink" <?php if($ec['event_category_color'] == 'pink') { echo 'checked="checked"';} ?> />
     <span class="pink-text">Pink</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="purple" <?php if($ec['event_category_color'] == 'purple') { echo 'checked="checked"';} ?> />
     <span class="purple-text">Purple</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="deep-purple" <?php if($ec['event_category_color'] == 'deep-purple') { echo 'checked="checked"';} ?> />
     <span class="deep-purple-text">Deep Purple</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="indigo" <?php if($ec['event_category_color'] == 'indigo') { echo 'checked="checked"';} ?> />
     <span class="indigo-text">Indigo</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="blue" <?php if($ec['event_category_color'] == 'blue') { echo 'checked="checked"';} ?> />
     <span class="blue-text">Blue</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="light-blue" <?php if($ec['event_category_color'] == 'light-blue') { echo 'checked="checked"';} ?> />
     <span class="light-blue-text">Light Blue</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="cyan" <?php if($ec['event_category_color'] == 'cyan') { echo 'checked="checked"';} ?> />
     <span class="cyan-text">Cyan</span>
     </label>
     </p> 
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="teal" <?php if($ec['event_category_color'] == 'teal') { echo 'checked="checked"';} ?> />
     <span class="teal-text">Teal</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="green" <?php if($ec['event_category_color'] == 'green') { echo 'checked="checked"';} ?> />
     <span class="green-text">Green</span>
     </label>
     </p>                        
     </div>
     <div class="col s6 m3">
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="light-green" <?php if($ec['event_category_color'] == 'light-green') { echo 'checked="checked"';} ?> />
     <span class="light-green-text">Light Green</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="lime" <?php if($ec['event_category_color'] == 'lime') { echo 'checked="checked"';} ?> />
     <span class="lime-text">Lime</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="yellow" <?php if($ec['event_category_color'] == 'yellow') { echo 'checked="checked"';} ?> />
     <span class="yellow-text">Yellow</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="amber" <?php if($ec['event_category_color'] == 'amber') { echo 'checked="checked"';} ?> />
     <span class="amber-text">Amber</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="orange" <?php if($ec['event_category_color'] == 'orange') { echo 'checked="checked"';} ?> />
     <span class="orange-text">Orange</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="deep-orange" <?php if($ec['event_category_color'] == 'deep-orange') { echo 'checked="checked"';} ?> />
     <span class="deep-orange-text">Deep Orange</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="brown" <?php if($ec['event_category_color'] == 'brown') { echo 'checked="checked"';} ?> />
     <span class="brown-text">Brown</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="grey" <?php if($ec['event_category_color'] == 'grey') { echo 'checked="checked"';} ?> />
     <span class="grey-text">Grey</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="blue-grey" <?php if($ec['event_category_color'] == 'blue-grey') { echo 'checked="checked"';} ?> />
     <span class="blue-grey-text">Blue Grey</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="sevent_category_color" value="black" <?php if($ec['event_category_color'] == 'black') { echo 'checked="checked"';} ?> />
     <span class="black-text">Black</span>
     </label>
     </p>
     </div>
     </div>
     
     <?php
}

if(isset($_POST['save_category'])) {
     $ename = addslashes($_POST['event_category_name']);
     $ecolor = $_POST['event_category_color'];
     $db->exec("UPDATE tbl_events_calendar_categories SET event_category_name = '$ename', event_category_color = '$ecolor' WHERE ec_id = '$_POST[ec_id]'");
     echo 'Category Updated';
}

if(isset($_POST['delete_category'])) {
     $db->exec("DELETE FROM tbl_events_calendar_categories WHERE ec_id = $_POST[ec_id]");
     echo 'Category Removed';
}

if(isset($_POST['new_category'])) {
     ?>
     <div class="row">
     <div class="input-field col s12 m6">
     <input type="text" name="event_category_name" id="event_category_name" />
     <label for="event_category_name" class="active">Category Name</label>
     </div>
     <div class="col s6 m3">
     <p>
     <label>
     <input type="radio" name="event_category_color" value="red" />
     <span class="red-text">Red</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="pink" />
     <span class="pink-text">Pink</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="purple" />
     <span class="purple-text">Purple</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="deep-purple" />
     <span class="deep-purple-text">Deep Purple</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="indigo" />
     <span class="indigo-text">Indigo</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="blue" />
     <span class="blue-text">Blue</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="light-blue" />
     <span class="light-blue-text">Light Blue</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="cyan" />
     <span class="cyan-text">Cyan</span>
     </label>
     </p> 
     <p>
     <label>
     <input type="radio" name="event_category_color" value="teal" />
     <span class="teal-text">Teal</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="green" />
     <span class="green-text">Green</span>
     </label>
     </p>                        
     </div>
     <div class="col s6 m3">
     <p>
     <label>
     <input type="radio" name="event_category_color" value="light-green" />
     <span class="light-green-text">Light Green</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="lime" />
     <span class="lime-text">Lime</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="yellow" />
     <span class="yellow-text">Yellow</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="amber" />
     <span class="amber-text">Amber</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="orange" />
     <span class="orange-text">Orange</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="deep-orange" />
     <span class="deep-orange-text">Deep Orange</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="brown" />
     <span class="brown-text">Brown</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="grey" />
     <span class="grey-text">Grey</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="blue-grey" />
     <span class="blue-grey-text">Blue Grey</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_category_color" value="black" checked="checked" />
     <span class="black-text">Black</span>
     </label>
     </p>
     </div>
     </div>
     
     <?php     
}

if(isset($_POST['add_category'])) {
     $db->exec("INSERT INTO tbl_events_calendar_categories (event_category_name, event_category_color) VALUES ('". addslashes($_POST['event_category_name']) ."', '$_POST[event_category_color]')");
     echo 'Category Added';
}

if(isset($_POST['edit_location'])) {
     $ecl = $db->query("SELECT * FROM tbl_events_calendar_locations WHERE el_id = $_POST[el_id]");
     $el = $ecl->fetch(PDO::FETCH_ASSOC);
     ?>
     <input type="hidden" name="sel_id" id="sel_id" value="<?php echo $el['el_id'] ?>" />
     <div class="row">
     <div class="col s12 m12 right-align">
     <a title="Delete Location" class="waves-effect waves-light btn-small red" href="#!" onclick="deleteLocation('<?php echo $el['el_id'] ?>')"><i class="material-icons">delete_forever</i></a>
     </div>
     </div>     
     <div class="row">
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">map</i>
     <input type="text" name="sevent_location_name" id="sevent_location_name" value="<?php echo $el['event_location_name'] ?>" />
     <label for="sevent_location_name" class="active">Location Name</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">phone</i>
     <input type="tel" name="sevent_location_phone" id="sevent_location_phone" value="<?php echo $el['event_location_phone'] ?>" />
     <label for="sevent_location_phone" class="active">Phone Number</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">home</i>
     <input type="text" name="sevent_location_address" id="sevent_location_address" value="<?php echo $el['event_location_address'] ?>" />
     <label for="sevent_location_address" class="active">Address</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">email</i>
     <input type="email" name="sevent_location_email" id="sevent_location_email" value="<?php echo $el['event_location_email'] ?>" />
     <label for="sevent_location_email" class="active">Email Address</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">location_city</i>
     <input type="text" name="sevent_location_city" id="sevent_location_city" value="<?php echo $el['event_location_city'] ?>" />
     <label for="sevent_location_city" class="active">City</label>
     </div>
     <div class="input-field col s6 m3">
     <select name="sevent_location_state" id="sevent_location_state">
     <?php echo $a->getStates($el['event_location_state']) ?>
     
     </select>
     <label>State</label>
     </div>
     <div class="input-field col s6 m3">
     <input type="text" name="sevent_location_zipcode" id="sevent_location_zipcode" value="<?php echo $el['event_location_zipcode'] ?>" maxlength="5" />
     <label for="sevent_location_zipcode" class="active">Zip Code</label>
     </div>
     <div class="input-field col s12 m12">
     <input type="text" name="sevent_location_comments" id="sevent_location_comments" value="<?php echo $el['event_location_comments'] ?>" />
     <label for="sevent_location_comments" class="active">Comments</label>
     </div>
     <div class="input-field col s12 m8">
     <input type="text" name="sevent_location_room" id="sevent_location_room" value="<?php echo $el['event_location_room'] ?>" />
     <label for="sevent_location_room" class="active">Room Name/Number</label>
     </div>
     <div class="input-field col s12 m4">
     <input type="number" name="sevent_location_floor" id="sevent_location_floor" value="<?php echo $el['event_location_floor'] ?>" />
     <label for="sevent_location_floor" class="active">Floor Number</label>
     </div>
     </div>
     <div class="row">
     <div class="file-field input-field">
     <div class="btn">
     <span>Event Image</span>
     <input type="file" name="sevent_location_image" id="sevent_location_image" accept="image/jpg, image/png, image/jpeg" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="Add or replace the Location Image" />
     </div>
     </div>
     <div class="col s12 m12">
     <img class="responsive-img materialboxed" style="width: 30%;" src="<?php echo $g['site_url'] ?>/content/assets/events/<?php echo $el['event_location_image'] ?>" />
     </div>
     </div>
     
     <?php
}

if(isset($_POST['save_location'])) {
     $root = $g['doc_root'] .'content/assets/events/';
     if($_FILES['event_location_image']['name'] != '') {
          $allowed = array('jpg', 'jpeg', 'png');
          $ext = strtolower(pathinfo($_FILES['event_location_image']['name'], PATHINFO_EXTENSION));
          if(!in_array($ext, $allowed)) {
               echo 'The file you attempted to upload is NOT a valid image file or either a jpeg or png image.  File not uploaded but rest of data processing.';
               unset($_POST['event_location_image']);
          } else {
               $filename = date('Ymdhis') . rand(0, 4) .'.'. $ext;
               $_POST['event_location_image'] = $filename;
               if(!move_uploaded_file($_FILES['event_location_image']['tmp_name'], $root . $filename)) {
                    echo 'There was a problem uploading the image.  Remaining data processing.';
                    unset($_POST['event_location_image']);
               }
          }
     }
     unset($_POST['save_location']);
     if($_POST['event_location_image'] == 'undefined') {
          unset($_POST['event_location_image']);
     }
     $el_id = $_POST['el_id'];
     unset($_POST['el_id']);
     $sql = "UPDATE tbl_events_calendar_locations SET ";
     foreach($_POST AS $k => $v) {
          $sql .= "`$k` = '". addslashes($v) ."', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= " WHERE el_id = '$el_id'";
     $db->exec($sql);
     echo 'Location Updated';
}

if(isset($_POST['new_location'])) {
     ?>
     <div class="row">
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">map</i>
     <input type="text" name="event_location_name" id="event_location_name" />
     <label for="event_location_name" class="active">Location Name</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">phone</i>
     <input type="tel" name="event_location_phone" id="event_location_phone" placeholder="Digits only!  NO symbols!" />
     <label for="event_location_phone" class="active">Phone Number</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">home</i>
     <input type="text" name="event_location_address" id="event_location_address" />
     <label for="event_location_address" class="active">Address</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">email</i>
     <input type="email" name="event_location_email" id="event_location_email" />
     <label for="event_location_email" class="active">Email Address</label>
     </div>
     <div class="input-field col s12 m6">
     <i class="material-icons prefix">location_city</i>
     <input type="text" name="event_location_city" id="event_location_city" />
     <label for="event_location_city" class="active">City</label>
     </div>
     <div class="input-field col s6 m3">
     <select name="event_location_state" id="event_location_state">
     <?php echo $a->getStates('none') ?>
     
     </select>
     <label>State</label>
     </div>
     <div class="input-field col s6 m3">
     <input type="text" name="event_location_zipcode" id="event_location_zipcode" maxlength="5" />
     <label for="event_location_zipcode" class="active">Zip Code</label>
     </div>
     <div class="input-field col s12 m12">
     <input type="text" name="event_location_comments" id="event_location_comments" />
     <label for="event_location_comments" class="active">Comments</label>
     </div>
     <div class="input-field col s12 m8">
     <input type="text" name="event_location_room" id="event_location_room" />
     <label for="event_location_room" class="active">Room Name/Number</label>
     </div>
     <div class="input-field col s12 m4">
     <input type="number" name="event_location_floor" id="event_location_floor" />
     <label for="event_location_floor" class="active">Floor Number</label>
     </div>
     </div>
     <div class="row">
     <div class="file-field input-field">
     <div class="btn">
     <span>Event Image</span>
     <input type="file" name="event_location_image" id="event_location_image" accept="image/jpg, image/png, image/jpeg" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="Add a Location Image" />
     </div>
     </div>
     </div>
     
     <?php
}

if(isset($_POST['add_location'])) {
     if($_FILES['event_location_image']['name'] != '') {
          $root = $g['doc_root'] .'content/assets/events/';
          $allowed = array('jpg', 'jpeg', 'png');
          $ext = strtolower(pathinfo($_FILES['event_location_image']['name'], PATHINFO_EXTENSION));
          if(!in_array($ext, $allowed)) {
               echo 'The file you attempted to upload is NOT a valid image file or either a jpeg or png image.  File not uploaded but rest of data processing.';
               unset($_POST['event_location_image']);
          } else {
               $filename = date('Ymdhis') . rand(0, 4) .'.'. $ext;
               $_POST['event_location_image'] = $filename;
               if(!move_uploaded_file($_FILES['event_location_image']['tmp_name'], $root . $filename)) {
                    echo 'There was a problem uploading the image.  Remaining data processing.';
                    unset($_POST['event_location_image']);
               }
          }
     }
     unset($_POST['add_location']);
     if($_POST['event_location_image'] == 'undefined') {
          unset($_POST['event_location_image']);
     }
     $sql = "INSERT INTO tbl_events_calendar_locations (";
     foreach($_POST AS $k => $v) {
          $sql .= "`$k`, ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ") VALUES (";
     foreach($_POST AS $k => $v) {
          $sql .= "'". addslashes($v) ."', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ")";
     $db->exec($sql);
     echo 'Location Added Successfully';
}

if(isset($_POST['delete_location'])) {
     $db->exec("DELETE FROM tbl_events_calendar_locations WHERE el_id = $_POST[el_id]");
     echo 'Location Removed';
}

if(isset($_POST['new_event'])) {
     ?>
     <div class="row">
     <div class="input-field col s12 m6">
     <input type="text" name="event_name" id="event_name" />
     <label for="event_name">Event Name</label>
     </div>
     <?php echo $evt->selectCategories() ?>
     
     <?php echo $evt->selectLocation() ?>
     
     <div class="input-field col s12">
     <textarea name="event_description" id="event_description" class="materialize-textarea" maxlength="3000" data-length="3000"></textarea>
     <label for="event_description">Event Details</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="datepicker" name="event_start_date" id="event_start_date" />
     <label for="event_start_date">Start Date</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="datepicker" name="event_end_date" id="event_end_date" />
     <label for="event_end_date">End Date</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="timepicker" name="event_start_time" id="event_start_time" />
     <label for="event_start_time">Start Time</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="timepicker" name="event_end_time" id="event_end_time" />
     <label for="event_end_time">End Time</label>
     </div>
     <div class="row">
     <div class="col s12 m6">
     <span class="form-text">Repeat Options</span>
     <p>
     <label>
     <input type="radio" name="event_repeated" value="0" checked="checked" />
     <span>Not Repeated</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_repeated" value="1" />
     <span>Repeated</span>
     </label>
     </p>
     </div>
     <div class="input-field col s12 m6">
     <select name="event_repeat_type" id="event_repeat_type" class="event_select">
     <option value="0" disabled selected>Select Interval</option>
     <option value="1">Daily</option>
     <option value="2">Weekly</option>
     <option value="3">Bi-Weekly</option>
     <option value="4">Monthly (same weekday)</option>
     <option value="5">Monthly (same day)</option>
     <option value="6">Annually</option>
     </select>
     <label>Repeat Interval</label>   
     </div>
     </div>
     <hr />
     <div class="row">
     <div class="input-field col s12 m6">
     <select name="event_calendar_id" id="event_calendar_id" class="event_select" onchange="checkBandP(this.value)">
     <option value="" disabled selected>Select Calendar</option>
     <?php echo $evt->getCalendarList(); ?>
     
     </select>
     <label>Calendar to Insert Event</label>
     </div>
     <div class="col s12 m6">
     <div class="file-field input-field">
     <div class="btn">
     <span>Event Image</span>
     <input type="file" name="event_featured_image" id="event_featured_image" accept="image/jpeg, image/png" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="Add the event's Featured Image" />
     </div>
     </div>
     </div>
     </div>
     <div class="row">
     <div class="col s12 m6" id="showBooking" style="display: none;">
     <span class="form-text">Enable Booking</span>
     <p>
     <label>
     <input type="radio" name="event_bookings_enabled" value="0" checked="checked" />
     <span>NO</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_bookings_enabled" value="1" />
     <span>YES</span>
     </label>
     </p>
     </div>
     <div class="col s12 m6" id="showPayment" style="display: none;">
     <span class="form-text">Enable Payments</span>
     <p>
     <label>
     <input type="radio" name="event_payments_enabled" value="0" checked="checked" />
     <span>NO</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_payments_enabled" value="1" />
     <span>YES</span>
     </label>
     </p>
     </div>
     </div>
     </div>
          
     <?php
}

if(isset($_POST['checkbandp'])) {
     $chk = $db->query("SELECT allow_bookings, allow_payments FROM tbl_events_calendar_settings WHERE es_id = $_POST[es_id]");
     $ck = $chk->fetch(PDO::FETCH_ASSOC);
     if($ck['allow_bookings'] == 0 && $ck['allow_payments'] == 0) {
          echo '0';
          die;
     }
     else if($ck['allow_bookings'] == 1 && $ck['allow_payments'] == 0) {
          echo '1';
          die;
     }
     else if($ck['allow_bookings'] == 0 && $ck['allow_payments'] == 1) {
          echo '2';
          die;
     }
     else if($ck['allow_bookings'] == 1 && $ck['allow_payments'] == 1) {
          echo '3';
          die;
     } else {
          echo '0';
          die;
     }               
}

if(isset($_POST['add_event'])) {
     if($_FILES['event_featured_image']['name'] != '') {
          $root = $g['doc_root'] .'content/assets/events/';
          $allowed = array("jpg", "jpeg", "png", "gif");
          $ext = strtolower(pathinfo($_FILES['event_featured_image']['name'], PATHINFO_EXTENSION));
          if(!in_array($ext, $allowed)) {
               echo 'Featured Image was not uploaded as the file type is not valid.<br />';
               unset($_POST['event_featured_image']);
          } else {
               $filename = date('Ymdhis') . rand(0, 4) .'.'. $ext;
               $_POST['event_featured_image'] = $filename;
               if(!move_uploaded_file($_FILES['event_featured_image']['tmp_name'], $root . $filename)) {
                    echo 'System error prevented the uploading of the Featured Image.<br />';
                    unset($_POST['event_featured_image']);
               }
          }
     }
     unset($_POST['add_event']);
     $_POST['event_category_ids'] = implode(",", $_POST['event_category_ids']);
     $sql = "INSERT INTO tbl_events_calendar (";
     foreach($_POST AS $k => $v) {
          $sql .= "`$k`, ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ") VALUES (";
     foreach($_POST AS $k => $v) {
          if($k == 'event_start_date' || $k == 'event_end_date') {
               $v = date('Y-m-d', strtotime($v));
          }
          if($k == 'event_start_time' || $k == 'event_end_time') {
               $v = date('h:i:s', strtotime($v));
          }
          $sql .= "'". addslashes($v) ."', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= ")";
     $db->exec($sql);
     unset($sql);
     if($_POST['event_repeated'] == 1) {
          $now = date_create($_POST['event_start_date']);
          $end = date_create($_POST['event_end_date']);
          $diff = date_diff($now, $end);
          $days = $diff->format('%a');
          $weeks = floor($days / 7);       
          $months = $diff->format('%m');
          $years = $diff->format('%y');          
          switch($_POST['event_repeat_type']) {              
               case 1:  // Daily repeat             
                    for($i=1;$i<=$days;$i++) {
                         $sql = "INSERT INTO tbl_events_calendar (";
                         foreach($_POST AS $k => $v) {
                              $sql .= "`$k`, ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ") VALUES (";
                         foreach($_POST AS $k => $v) {
                              if($k == 'event_start_date') {
                                   $v = date('Y-m-d', strtotime($v ."+$i day"));
                              }
                              if($k == 'event_end_date') {
                                  $v = date('Y-m-d', strtotime($v)); 
                              }
                              if($k == 'event_start_time' || $k == 'event_end_time') {
                                   $v = date('h:i:s', strtotime($v));
                              }
                              $sql .= "'". addslashes($v) ."', ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ")";
                         $db->exec($sql);
                         unset($sql);
                    }
                    break;
               case 2: // Weekly repeat
                    for($i=1;$i<=$weeks;$i++) {
                         $sql = "INSERT INTO tbl_events_calendar (";
                         foreach($_POST AS $k => $v) {
                              $sql .= "`$k`, ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ") VALUES (";
                         foreach($_POST AS $k => $v) {
                              if($k == 'event_start_date') {
                                   $v = date('Y-m-d', strtotime($v ."+$i week"));
                              }
                              if($k == 'event_end_date') {
                                  $v = date('Y-m-d', strtotime($v)); 
                              }
                              if($k == 'event_start_time' || $k == 'event_end_time') {
                                   $v = date('h:i:s', strtotime($v));
                              }
                              $sql .= "'". addslashes($v) ."', ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ")";
                         $db->exec($sql);
                         unset($sql);
                    }                    
                    break;
               case 3: // Bi-weekly
                    $r = 1;
                    for($i=1;$i<$weeks - 1;$i++) {
                         $r = $i * 2;
                         $sql = "INSERT INTO tbl_events_calendar (";
                         foreach($_POST AS $k => $v) {
                              $sql .= "`$k`, ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ") VALUES (";
                         foreach($_POST AS $k => $v) {
                              if($k == 'event_start_date') {
                                   $v = date('Y-m-d', strtotime($v ."+$r week"));
                              }
                              if($k == 'event_end_date') {
                                  $v = date('Y-m-d', strtotime($v)); 
                              }
                              if($k == 'event_start_time' || $k == 'event_end_time') {
                                   $v = date('h:i:s', strtotime($v));
                              }
                              $sql .= "'". addslashes($v) ."', ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ")";
                         $db->exec($sql);
                         unset($sql);
                    }                    
                    break;
               case 4: // Monthly on same weekday
                    $weekday = date('l', strtotime($_POST['event_start_date']));
                    $week = $a->getWeeks($_POST['event_start_date']);               
                    for($i=1;$i<=$months;$i++) {
                         $sql = "INSERT INTO tbl_events_calendar (";
                         foreach($_POST AS $k => $v) {
                              $sql .= "`$k`, ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ") VALUES (";
                         foreach($_POST AS $k => $v) {
                              if($k == 'event_start_date') {
                                   $tmpdate = date('Y-m-d', strtotime($v ."+$i month"));
                                   $tmpdate = date('Y-m', strtotime($tmpdate));
                                   $v = date('Y-m-d', strtotime("$week $weekday $tmpdate"));
                                   unset($tmpdate);                                   
                                   
                              }
                              if($k == 'event_end_date') {
                                  $v = date('Y-m-d', strtotime($v)); 
                              }
                              if($k == 'event_start_time' || $k == 'event_end_time') {
                                   $v = date('h:i:s', strtotime($v));
                              }
                              $sql .= "'". addslashes($v) ."', ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ")";
                         $db->exec($sql);
                         unset($sql);
                    }
                    break;
               case 5: // Monthly on same day
                    for($i=1;$i<=$months;$i++) {
                         $sql = "INSERT INTO tbl_events_calendar (";
                         foreach($_POST AS $k => $v) {
                              $sql .= "`$k`, ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ") VALUES (";
                         foreach($_POST AS $k => $v) {
                              if($k == 'event_start_date') {
                                   $v = date('Y-m-d', strtotime($v ."+$i month"));
                              }
                              if($k == 'event_end_date') {
                                  $v = date('Y-m-d', strtotime($v)); 
                              }
                              if($k == 'event_start_time' || $k == 'event_end_time') {
                                   $v = date('h:i:s', strtotime($v));
                              }
                              $sql .= "'". addslashes($v) ."', ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ")";
                         $db->exec($sql);
                         unset($sql);
                    }                    
                    break;
               case 6: // Annually
                    for($i=1;$i<=$years;$i++) {
                         $sql = "INSERT INTO tbl_events_calendar (";
                         foreach($_POST AS $k => $v) {
                              $sql .= "`$k`, ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ") VALUES (";
                         foreach($_POST AS $k => $v) {
                              if($k == 'event_start_date') {
                                   $v = date('Y-m-d', strtotime($v ."+$i year"));
                              }
                              if($k == 'event_end_date') {
                                  $v = date('Y-m-d', strtotime($v)); 
                              }
                              if($k == 'event_start_time' || $k == 'event_end_time') {
                                   $v = date('h:i:s', strtotime($v));
                              }
                              $sql .= "'". addslashes($v) ."', ";
                         }
                         $sql = rtrim($sql, ", ");
                         $sql .= ")";
                         $db->exec($sql);
                         unset($sql);
                    }                    
                    break;
               default:
                    break;
          }
     }
     echo 'Event Created Successfully';
}

if(isset($_POST['edit_event'])) {
     $ev = $db->query("SELECT * FROM tbl_events_calendar WHERE ev_id = $_POST[ev_id]");
     $et = $ev->fetch(PDO::FETCH_ASSOC);
     ?>
     <input type="hidden" name="ev_id" id="ev_id" value="<?php echo $et['ev_id'] ?>" />
     <div class="row">
     <div class="input-field col s12 m6">
     <input type="text" name="event_name" id="event_name" value="<?php echo $et['event_name'] ?>" />
     <label for="event_name" class="active">Event Name</label>
     </div>
     <?php echo $evt->selectCategories($et['event_category_ids']) ?>
     
     <?php echo $evt->selectLocation($et['event_location_id']) ?>
     
     <div class="input-field col s12">
     <textarea name="event_description" id="event_description" class="materialize-textarea" maxlength="3000" data-length="3000"><?php echo stripslashes($et['event_description']) ?></textarea>
     <label for="event_description" class="active">Event Details</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="datepicker" name="event_start_date" id="event_start_date" value="<?php echo date('M j, Y', strtotime($et['event_start_date'])) ?>" />
     <label for="event_start_date" class="active">Start Date</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="datepicker" name="event_end_date" id="event_end_date" value="<?php echo date('M j, Y', strtotime($et['event_end_date'])) ?>" />
     <label for="event_end_date" class="active">End Date</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="timepicker" name="event_start_time" id="event_start_time" value="<?php echo date('h:i A', strtotime($et['event_start_time'])) ?>" />
     <label for="event_start_time" class="active">Start Time</label>
     </div>
     <div class="input-field col s12 m6">
     <input type="text" class="timepicker" name="event_end_time" id="event_end_time" value="<?php echo date('h:i A', strtotime($et['event_end_time'])) ?>" />
     <label for="event_end_time" class="active">End Time</label>
     </div>
     <div class="row">
     <div class="col s12 m6">
     <span class="form-text">Repeat Options</span>
     <p>
     <label>
     <input type="radio" name="event_repeated" value="0" checked="checked" <?php if($et['event_repeated'] == 0) { echo 'checked="checked"'; } ?> />
     <span>Not Repeated</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_repeated" value="1" <?php if($et['event_repeated'] == 1) { echo 'checked="checked"'; } ?> />
     <span>Repeated</span>
     </label>
     </p>
     </div>
     <div class="input-field col s12 m6">
     <select name="event_repeat_type" id="event_repeat_type" class="event_select">
     <option value="0" disabled selected>Select Interval</option>
     <option value="1" <?php if($et['event_repeat_type'] == 1) { echo 'selected="selected"'; } ?>>Daily</option>
     <option value="2" <?php if($et['event_repeat_type'] == 2) { echo 'selected="selected"'; } ?>>Weekly</option>
     <option value="3" <?php if($et['event_repeat_type'] == 3) { echo 'selected="selected"'; } ?>>Bi-Weekly</option>
     <option value="4" <?php if($et['event_repeat_type'] == 4) { echo 'selected="selected"'; } ?>>Monthly (same weekday)</option>
     <option value="5" <?php if($et['event_repeat_type'] == 5) { echo 'selected="selected"'; } ?>>Monthly (same day)</option>
     <option value="6" <?php if($et['event_repeat_type'] == 6) { echo 'selected="selected"'; } ?>>Annually</option>
     </select>
     <label>Repeat Interval</label>   
     </div>
     </div>
     <hr />
     <div class="row">
     <div class="input-field col s12 m6">
     <select name="event_calendar_id" id="event_calendar_id" class="event_select" onchange="checkBandP(this.value)">
     <option value="" disabled selected>Select Calendar</option>
     <?php echo $evt->getCalendarList($et['event_calendar_id']); ?>
     
     </select>
     <label>Calendar to Insert Event</label>
     </div>
     <div class="col s12 m6">
     <div class="file-field input-field">
     <div class="btn">
     <span>Event Image</span>
     <input type="file" name="event_featured_image" id="event_featured_image" accept="image/jpeg, image/png" />
     </div>
     <div class="file-path-wrapper">
     <input class="file-path validate" type="text" placeholder="Change the event's Featured Image" />
     </div>
     </div><br />
     <img src="<?php echo $g['site_url'] ?>/content/assets/events/<?php echo $et['event_featured_image'] ?>" class="responsive-img materialboxed" style="width: 40%;" />
     </div>
     </div>
     <div class="row">
     <div class="col s12 m6" id="showBooking">
     <span class="form-text">Enable Booking</span>
     <p>
     <label>
     <input type="radio" name="event_bookings_enabled" value="0" checked="checked" <?php if($et['event_bookings_enabled'] == 0) { echo 'checked="checked"'; } ?> />
     <span>NO</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_bookings_enabled" value="1" <?php if($et['event_bookings_enabled'] == 1) { echo 'checked="checked"'; } ?> />
     <span>YES</span>
     </label>
     </p>
     </div>
     <div class="col s12 m6" id="showPayment">
     <span class="form-text">Enable Payments</span>
     <p>
     <label>
     <input type="radio" name="event_payments_enabled" value="0" checked="checked" <?php if($et['event_payments_enabled'] == 0) { echo 'checked="checked"'; } ?> />
     <span>NO</span>
     </label>
     </p>
     <p>
     <label>
     <input type="radio" name="event_payments_enabled" value="1" <?php if($et['event_payments_enabled'] == 1) { echo 'checked="checked"'; } ?> />
     <span>YES</span>
     </label>
     </p>
     </div>
     </div>
     </div>     
     
     <?php
}

if(isset($_POST['save_event'])) {
     if($_FILES['event_featured_image']['name'] != '') {
          $root = $g['doc_root'] .'content/assets/events/';
          $allowed = array("jpg", "jpeg", "png", "gif");
          $ext = strtolower(pathinfo($_FILES['event_featured_image']['name'], PATHINFO_EXTENSION));
          if(!in_array($ext, $allowed)) {
               echo 'Featured Image was not uploaded as the file type is not valid.<br />';
               unset($_POST['event_featured_image']);
          } else {
               $filename = date('Ymdhis') . rand(0, 4) .'.'. $ext;
               $_POST['event_featured_image'] = $filename;
               if(!move_uploaded_file($_FILES['event_featured_image']['tmp_name'], $root . $filename)) {
                    echo 'System error prevented the uploading of the Featured Image.<br />';
                    unset($_POST['event_featured_image']);
               }
          }
     } else {
          unset($_POST['event_featured_image']);
     }
     unset($_POST['save_event']);
     $ev_id = $_POST['ev_id'];
     unset($_POST['ev_id']);
     $_POST['event_category_ids'] = implode(",", $_POST['event_category_ids']);
     $sql = "UPDATE tbl_events_calendar SET ";
     foreach($_POST AS $k => $v) {
          if($k == 'event_start_date' || $k == 'event_end_date') {
               $v = date('Y-m-d', strtotime($v));
          }
          if($k == 'event_start_time' || $k == 'event_end_time') {
               $v = date('h:i:s', strtotime($v));
          }               
          $sql .= "`$k` = '". addslashes($v) ."', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= " WHERE ev_id = $ev_id";
     $db->exec($sql);
     echo 'Event Updated Successfully'; 
}

if(isset($_POST['delete_event'])) {
     $db->exec("UPDATE tbl_events_calendar SET event_status = 9 WHERE ev_id = $_POST[ev_id]");
     echo 'Event Deleted';
}

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

if(isset($_POST['item'])) {
     $i = 0;
     foreach($_POST['item'] AS $value) {
          $db->exec("UPDATE tbl_carousel_slides SET cs_order = $i WHERE cs_id = $value");
          $i++;
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
}

if(isset($_POST['change_slide_type'])) {
     ?>
     <h4 class="title">Change Slide Type</h4>
     <p>This option is currently unavailable but will be added in a later update.</p>
     <?php
}

if(isset($_POST['update_slide_type'])) {
     die;
}

if(isset($_POST['show_slide'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_status = 1 WHERE cs_id = $_POST[cs_id]");
}

if(isset($_POST['hide_slide'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_status = 0 WHERE cs_id = $_POST[cs_id]");     
}

if(isset($_POST['remove_slide'])) {
     $db->exec("UPDATE tbl_carousel_slides SET cs_status = 9 WHERE cs_id = $_POST[cs_id]");
}

if(isset($_POST['edit_content'])) {
     $cont = $db->query("SELECT * FROM tbl_content WHERE menu_id = $_POST[menu_id]");
}

if(isset($_POST['save_edit'])) {
     $content = $db->quote($_POST['save_content']);
     $db->exec("UPDATE tbl_content SET section_content = $content WHERE p_id = $_POST[save_page] AND section_order = $_POST[save_section]");
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
     echo $content;
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
     <p><input name="nmenu_status" id="mstatus1" value="1" type="radio" class="with-gap" /><label for="mstatus1"><span>Published</span></label></p>
     <p><input name="nmenu_status" id="mstatus2" value="2" type="radio" class="with-gap" /><label for="mstatus2"><span>Hidden</span></label></p>
     <p><input name="nmenu_status" id="mstatus0" value="0" type="radio" class="with-gap" checked="checked" /><label for="mstatus0"><span>Draft</span></label></p>
     </div>
     </div>
     </div>     
     <?php     
}

if(isset($_POST['get_children'])) {
     $child = $db->query("SELECT m_id, menu_name, menu_order FROM tbl_menu WHERE menu_parent_id = $_POST[parent] ORDER BY menu_order");
     echo '<option value="0" selected>Place First</option>';
     while($c = $child->fetch(PDO::FETCH_ASSOC)) {
          echo '<option value="'. $c['menu_order'] .'">Place after: '. stripslashes($c['menu_name']) .'</option>';
     }
}

if(isset($_POST['save_new_page'])) {
     $_POST['menu_order'] = $_POST['menu_order'] + 1;
     $_POST['menu_name'] = addslashes($_POST['menu_name']);
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

if(isset($_POST['delete_image'])) {
     if($_POST['image_type'] == 'li') {
          $type = 'landing_image';
     }
     if($_POST['image_type'] == 'si') {
          $type = 'seo_image';
     }
     $db->exec("UPDATE tbl_content SET `$type` = '' WHERE p_id = $_POST[p_id]");
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
          $ext = pathinfo($_FILES['seo_image']['name'], PATHINFO_EXTENSION);
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
     $sql = "UPDATE tbl_content SET ";
     foreach($_POST AS $f => $v) {
          $sql .= "`$f` = '$v', ";
     }
     $sql = rtrim($sql, ", ");
     $sql .= " WHERE `p_id` = $p_id";
     echo $sql;
     $db->exec($sql);
     $db->exec("UPDATE tbl_menu SET menu_status = $menu_status, menu_link = '$menu_link' WHERE m_id = $m_id");    
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
}

if(isset($_POST['update_value'])) {
	if($_POST['field'] == 'phone_1' || $_POST['field'] == 'phone_2' || $_POST['field'] == 'fax_1') {
		$_POST['value'] = preg_replace("/[^0-9]/", "", str_replace(" ","", $_POST['value']));
	}	
	$db->exec("UPDATE tbl_globals SET `$_POST[field]` = '$_POST[value]' WHERE g_id = 1");
}

if(isset($_POST['update_sermon_config'])) {
     $db->exec("UPDATE tbl_sermons_settings SET `$_POST[field]` = '$_POST[value]' WHERE s_id = 1");
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
			<input type="checkbox" id="company_onlyl" onchange="showCompany('#company_onlyl')" <?php if($bl['block_content'] == 'company') { echo 'checked="checked"';} ?> />
			<label for="company_onlyl">Check to populate this block with your company information (overrides what you enter below).</label>
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
			<input type="checkbox" id="company_onlym" onchange="showCompany('#company_onlym')" <?php if($bl['block_content'] == 'company') { echo 'checked="checked"';} ?> />
			<label for="company_onlym">Check to populate this block with your company information (overrides what you enter below).</label>
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
			<input type="checkbox" id="company_onlyr" onchange="showCompany('#company_onlyr')" <?php if($bl['block_content'] == 'company') { echo 'checked="checked"';} ?> />
			<label for="company_onlyr">Check to populate this block with your company information (overrides what you enter below).</label>
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
               <input type="radio" name="navbar_type" id="type_fixed" value="f" <?php if($b->getBlockValue('navf') == 'f') { echo 'checked="checked"'; } ?> onchange="updateBlock('navf', 'f')" />
               <label for="type_fixed">Fixed Nabvar</label><br />
               <input type="radio" name="navbar_type" id="type_fluid" value="l" <?php if($b->getBlockValue('navf') == 'l') { echo 'checked="checked"'; } ?> onchange="updateBlock('navf', 'l')" />
               <label for="type_fluid">Fluid Nabvar (default)</label>               
               </p>
               </div>
               </div>
               <div class="row">
               <div class="col s12 m4 l4">
               <p>
               <input type="radio" name="menu_align" id="align_left" value="l" <?php if($b->getBlockValue('navm') == 'l') { echo 'checked="checked"'; } ?> onchange="updateBlock('navm', 'l')" />
               <label for="align_left">Menu Alignment Left</label><br />
               <input type="radio" name="menu_align" id="align_right" value="l" <?php if($b->getBlockValue('navm') == 'r') { echo 'checked="checked"'; } ?> onchange="updateBlock('navm', 'r')" />
               <label for="align_right">Menu Alignment Right</label>               
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
}

if(isset($_POST['save_block_content'])) {
	$blockcontent = $db->quote($_POST['block_content']);
	$db->exec("UPDATE tbl_blocks SET block_content = $blockcontent WHERE block_area = '$_POST[block_area]'");
}

if(isset($_POST['save_block_company'])) {
	$db->exec("UPDATE tbl_blocks SET block_content = 'company' WHERE block_area = '$_POST[block_area]'");
}
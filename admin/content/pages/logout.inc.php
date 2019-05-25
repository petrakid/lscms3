<?php
$db->exec("UPDATE tbl_users SET cookie_hash = '' WHERE user_id = '". $_SESSION['user']['user_id'] ."'");
setcookie('remlog', '', time() - 1, "/");
session_destroy();
$_SESSION = array();
echo '<meta http-equiv="refresh" content="0; url='. $g['site_url'] .'" />';
exit;
?>

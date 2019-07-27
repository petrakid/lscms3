<?php
error_reporting(-1);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', TRUE); // shut this off in production environment
ini_set('html_errors', TRUE);
ini_set("log_errors", 1);
ini_set("error_log", "/home/dinubalu/public_html/logs/error.log");
ini_set('log_errors_max_len', 1024);

$paths = explode(PATH_SEPARATOR,get_include_path());
$paths[] = '/home/dinubalu/php/';
$path_combined = implode(PATH_SEPARATOR,$paths);

set_include_path($path_combined);
ini_set('include_path',$path_combined);

session_start();

include 'includes/ls-config.php';

try {
     $dsn = "mysql:host=". DB_HOST .";dbname=". DB_NAME;
     $db = new PDO($dsn, DB_USER, DB_PASSWORD);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
     echo "Connection to Database failed: ". $e->getMessage();
}

require_once('includes/ls-cron.php');

$glb = $db->query("SELECT * FROM tbl_globals WHERE g_id = 1");
$g = $glb->fetch(PDO::FETCH_ASSOC);
$_SESSION['site_url'] = $g['site_url'];

include 'includes/ls-functions.php';
include 'includes/ls-classes.php';
include 'ls-admin/includes/admin.class.php';

$b = new Blocks($db);
$m = new Menu($db);     
$p = new Page($db); 
$sm = new SocialMedia($db);
$st = new Style($db);

$sec = new Security($db);
if($sec->checkLoad($_SERVER['SCRIPT_NAME']) == 1) {
     die("You cannot load this site by the method you chose.");
}

if(!empty($_COOKIE['remlog'])) {
     $sec->checkCookie($_COOKIE['remlog']);
}

if(!isset($_SESSION['isLoggedIn']) && ($_GET['p'] == 'admin/login/' || $_GET['p'] == 'admin')) {
     include 'ls-admin/index.php';
     die();
}

if(!isset($_GET['p'])) {
     $_GET['p'] = '';
}

if($g['maintenance'] == 1) {
     if(!isset($_SESSION['isLoggedIn'])) {
          header("location:maintenance.php");
          die('Maintenance Mode Active.');
     }
}

if(isset($_SESSION['isLoggedIn'])) {
     $a = new Admin($db); 
     $plg = new Plugins($db);
}

$plug = new Plugin($db);

if(strpos($_GET['p'], 'admin/') !== false) {
     $exp = explode("/", $_GET['p']);
     $_GET['p'] = @$exp[0];
     $_GET['s'] = @$exp[1];
     $_GET['f'] = @$exp[2];
     $_GET['d'] = @$exp[3];
     if($_GET['s'] == '') {
          $_GET['s'] = 'dashboard';
     }

}

switch($_GET['p']) {
     case 'admin':
          include 'ls-admin/index.php';
          break;
     case '':
          $_GET['p'] = 'home';
          include 'content/header.php';
          include 'content/menu.php';          
          include 'content/page.php';
          include 'content/footer.php';
          break;
     default:
          include 'content/header.php';
          include 'content/menu.php';     
          include 'content/page.php';
          include 'content/footer.php';
          break;
}
?>

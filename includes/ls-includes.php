<?php
session_start();

include 'ls-config.php';

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

if(isset($_POST['add_resource_count'])) {
     $db->exec("UPDATE tbl_downloads SET download_count = download_count + 1 WHERE d_id = $_POST[d_id]");
}

if(isset($_POST['set_calendar'])) {
     if($_POST['es_id'] == 0) {
          $db->exec("UPDATE tbl_events_calendar_settings SET event_calendar_page_id = 0 WHERE event_calendar_page_id = $_POST[page]");
     } else {
          $db->exec("UPDATE tbl_events_calendar_settings SET event_calendar_page_id = $_POST[page] WHERE es_id = $_POST[es_id]");
     }
     echo 'Calendar Selected';
}

if(isset($_POST['view_event_details'])) {
     echo 'works';
}
?>
<?php
session_start();
function __autoload($class_name) {
    require_once('../cls/class.' . strtolower($class_name) . '.php');
}
require_once('../inc/functions.inc.php');
$_SESSION['Login_Search'] = false;
$_SESSION['username'] = '';
session_destroy();
transfers_to('./login.php');
exit;

?>
<?php
session_start();
function __autoload($class_name) {
    require_once('../cls/class.' . strtolower($class_name) . '.php');
}
require_once('../inc/functions.inc.php');
require_once('../inc/config.inc.php');
if(!$_SESSION['Login_Search']){ transfers_to('login.php'); }
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Kết nối doanh nghiệp">
    <meta name="keywords" content="Kết nối doanh nghiệp">
    <meta name="author" content="Phan Minh Trung - trungminhphan@gmail.com">
    <link rel="shortcut icon" type="image/x-icon" href="../images/favicon.ico" />
    <title>Quản lý công văn - Trường Đại học An Giang</title>
    <link href="../css/metro.css" rel="stylesheet">
    <link href="../css/metro-icons.css" rel="stylesheet">
    <link href="../css/metro-responsive.css" rel="stylesheet">
    <link href="../css/metro-schemes.css" rel="stylesheet">
    <script src="../js/jquery-2.1.3.min.js"></script>
    <script src="../js/metro.js"></script>
</head>
<body >
<!-- ----------------------- menu -------------------->
<div class="app-bar fixed-top" data-role="appbar">
        <a href="index.php" class="app-bar-element branding"><span class="mif-home mif-2x"></span> <b>TRA CỨU CÔNG VĂN</b></a>
        <ul class="app-bar-menu small-dropdown place-right">
            <li><a href="logout.php"><span class="mif-exit"></span>&nbsp;&nbsp;Đăng xuất [<?php echo $_SESSION['username'] . '@agu.edu.vn'; ?>]</a></li>
        </ul>
        <span class="app-bar-pull"></span>
    </div>
</div>
<!-- ---------------- end menu --------------- -->
<div class="container page-content" style="margin-top: 80px;">
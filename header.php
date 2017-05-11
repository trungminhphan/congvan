<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
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
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
    <title>Quản lý công văn - Trường Đại học An Giang</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/metro.js"></script>
</head>
<body >
<!-- ----------------------- menu -------------------->
<div class="app-bar fixed-top" data-role="appbar">
        <a href="index.php" class="app-bar-element branding"><span class="mif-home mif-2x"></span></a>
        <ul class="app-bar-menu small-dropdown">
            <li><a href="#" class="dropdown-toggle"><span class="mif-apps mif-2x"></span>&nbsp;&nbsp;Danh mục</a>
                <ul class="d-menu" data-role="dropdown">
                    <li><a href="loaivanban.php"><span class="mif-earth"></span>&nbsp;&nbsp;Loại văn bản</a></li>
                    <li class="divider"></li>
                    <li><a href="linhvuc.php"><span class="mif-share"></span>&nbsp;&nbsp;Lĩnh vực</a></li>
                    <li class="divider"></li>
                    <li><a href="donvisoanthao.php"><span class="mif-coins"></span>&nbsp;&nbsp;Đơn vị soạn thảo</a></li>
                    <li class="divider"></li>
                    <li><a href="emailaccount.php"><span class="mif-mail"></span>&nbsp;&nbsp;Danh sách Email</a></li>
                    <li class="divider"></li>
                    <li><a href="emailgroup.php"><span class="mif-organization"></span>&nbsp;&nbsp;Nhóm Email</a></li>
                    <li class="divider"></li>
                </ul>
            </li>
            <li><a href="nhaplieu.php"><span class="mif-insert-template mif-2x"></span>&nbsp;&nbsp;Nhập liệu</a></li>
            <li><a href="#" class="dropdown-toggle"><span class="mif-chart-line mif-2x"></span>&nbsp;&nbsp;Thống kê</a>
                <ul class="d-menu" data-role="dropdown">
                    <li><a href="timcongvan.php"><span class="mif-search"></span> Tìm Công văn</a></li>
                    <li class="divider"></li>
                    <li><a href="timtheoloaicongvan.php"><span class="mif-zoom-in"></span> Tìm theo loại công văn</a></li>
                    <li class="divider"></li>
                    <li><a href="danhsachcongvanden.php"><span class="mif-folder-open"></span> Danh sách Công văn đến</a></li>
                    <li class="divider"></li>
                    <li><a href="danhsachcongvandi.php"><span class="mif-folder-special"></span> Danh sách Công văn đi</a></li>
                    <li class="divider"></li>
                    <li><a href="thongketheongay.php"><span class="mif-calendar"></span> Thống kê theo ngày</a></li>
                    <li class="divider"></li>
                    <li><a href="thongkebaocao.php"><span class="mif-layers"></span> Thống kê báo cáo</a></li>
                </ul>
            </li>
            <li><a href="" class="dropdown-toggle"><span class="mif-users"></span>&nbsp;&nbsp;Tài khoản</a>
                <ul class="d-menu" data-role="dropdown">
                    <li><a href="users.php"><span class="mif-user"></span>&nbsp;&nbsp;Quản lý tài khoản</a></li>
                    <li class="divider"></li>
                    <li><a href="change_password.php"><span class="mif-sync-disabled"></span>&nbsp;&nbsp;Thay đổi mật khẩu</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><span class="mif-exit"></span>&nbsp;&nbsp;Đăng xuất</a></li>
                    <li class="divider"></li>
                </ul>
            </li>
        </ul>
        <span class="app-bar-pull"></span>
    </div>
</div>
<!-- ---------------- end menu --------------- -->
<div class="container page-content" style="margin-top: 80px;">
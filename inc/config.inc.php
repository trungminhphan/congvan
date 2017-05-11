<?php
	//DEFINE QUYEN CHO TUNG NGUOI
	define("ADMIN", 1);
	define("MANAGER", 2);
	define("UPDATER", 4);

	//define("VIEWER", 8);
	//$g_config['db_server'] = 'mysql.agu.edu.vn';
	/*$g_config['db_server'] = 'localhost';
	$g_config['db_username'] = 'root';
	$g_config['db_password'] = 'root';
	$g_config['db_database_name'] = 'ptth';
	$g_config['image_folder'] = 'images/';*/

	//$mongo_server = 'localhost';
	//$mongo_username = 'admin';
	//$mongo_password = 'admin';
	//$mongo_database_name = 'ptth';

	//$g_config['admin_user'] = 'admin';
	//$g_config['admin_password'] = MD5('pmtrung');
	//$g_config['admin_password'] = '6701a36f285636bfbf4afc07bed8fc0c';
	
	//$hash_password["crypt"] = 'MD5';
	//$hash_password["password"] = 'PASSWORD';
	//$hash_password[""] = 'PLAIN_TEXT';
	
	//smtp config for send mail
	$items_per_group = 30;
	$items_per_group_dicu = 30;
	$items_per_group_thongke = 50;
	$items_per_group_ctcd = 300;
	$valid_formats = array("jpg", "png", "gif", "zip", "bmp", "doc", "docx", "pdf", "xls", "xlsx", "ppt", "pptx");
	$target_files = 'uploads/';
?>
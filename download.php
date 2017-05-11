<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
$id = isset($_GET['id']) ? $_GET['id']: '';
$k = isset($_GET['key']) ? $_GET['key']: 0;
$filename = ''; $name = '';

$congvan = new CongVan();
$congvan->id = $id; $cv = $congvan->get_one();
if($cv['dinhkem']){
	foreach ($cv['dinhkem'] as $key => $value) {
		if($key == $k){
			$filename = $target_files . $value['alias_name'];
            $name = $target_files .$value['filename'];
		} 
	}
}

if (file_exists($filename)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($name).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
    exit;
} else {
    echo 'Sorry! Tập tin không tồn tại.';
}
?>

<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
require_once('inc/config.inc.php');
//$session = new SessionManager();
//$users = new Users();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
//if(!$users->isLoggedIn()){ transfers_to('./login.php'); }
$id = isset($_GET[md5('id')]) ? $_GET[md5('id')] : '';
$id_emailaccount = isset($_GET[md5('id_email')]) ? $_GET[md5('id_email')] : '';
$congvan = new CongVan();
$congvan->id = $id; $cv = $congvan->get_one();
$congvan->set_read($id_emailaccount);
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
    <script type="text/javascript" src="js/jquery.media.js"></script>
    <script type="text/javascript" src="js/jquery.metadata.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#open_panel").hide();
            $('a.media').media({width:'100%', height: 800});
            $("a.list_media").click(function(){
                var media_html = '<a class="media" href="'+$(this).attr('href')+'"></a>';
                $("#show_pdf").html(media_html);
                $('a.media').media({width:'100%', height: 800});
                //$("a.media").attr("href", $(this).attr('href'));
                //alert($(this).attr('href'));
            });
            $("#close_panel").click(function(){
                $("#panel_control").fadeOut();
                $(this).hide();$("#open_panel").show();
            });
            $("#open_panel").click(function(){
                 $("#panel_control").fadeIn();
                $(this).hide();$("#close_panel").show();
            });
        });
    </script>
</head>
<body>
<div class="padding10" id="panel_control">
    <img src="images/logo_agu.png" width="50" align="left" style="padding-top: 10px;padding-right:10px;" />
    <h2>Phòng Hành chính Tổng hợp - Trường Đại học An Giang</h2>
    <b>Danh sách tập tin đính kèm:</b>
    <?php
        $arr_pdf_files = array();
        if($cv['dinhkem']){
            echo '<ul class="numeric-list blue-bullet">';
            foreach ($cv['dinhkem'] as $key => $value) {
                $icon = show_icon($value['filetype']);
                if($value['filetype'] == 'pdf'){
                    array_push($arr_pdf_files, $target_files . $value['alias_name']);
                    echo '<li>'.$icon . '<a class="list_media" href="'.$target_files . $value['alias_name'].'" onclick="return false;">'.$value['filename'].'</a></li>';
                } else {
                    echo '<li>'.$icon . '<a href="'.$target_files . $value['alias_name'].'">'.$value['filename'].'</a> (Download)</li>';
                }
            }
            echo '</ul>';
        }
    ?>
</div>
<a href="#" id="close_panel" class="button danger" style="position: absolute;top:0;right:0;"><span class="mif-cross"></span> Đóng</a>
<a href="#" id="open_panel" class="button primary" style="position: absolute;top:0;right:0;"><span class="mif-folder-open"></span> Mở</a>
<hr />
<?php
if($arr_pdf_files && count($arr_pdf_files) > 0){
    echo '<div id="show_pdf"><a class="media" href="'.$arr_pdf_files[0].'"></a></div>';
}
/*
if($cv['dinhkem']){
    foreach ($cv['dinhkem'] as $key => $value) {
        if($value['filetype'] == 'pdf'){
            echo '<a class="media" href="'.$target_files . $value['alias_name'].'"></a>';
        } else {
            echo '<div style="padding: 10px;">';
            $icon = show_icon($value['filetype']);
            echo $icon . ' Download: <a href="'.$target_files . $value['alias_name'].'">'.$value['filename'].'</a> <br />';
            echo '</div>';
        }
    }
}*/
?>
</body>
</html>
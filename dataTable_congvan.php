
<?php
function __autoload($class_name) {
    require_once('cls/class.' . strtolower($class_name) . '.php');
}
$session = new SessionManager();
$users = new Users();$loaicongvan = new LoaiVanBan();
require_once('inc/functions.inc.php');
require_once('inc/config.inc.php');
if(!$users->isLoggedIn()){ transfers_to('./login.php'); }

$id = isset($_GET['id']) ? $_GET['id'] : '';
$start = isset($_GET['start']) ? $_GET['start'] : 0;  
$length = isset($_GET['length']) ? $_GET['length'] : 0; 
$draw = isset($_GET['draw']) ? $_GET['draw'] : 1; 
$keysearch = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$arr_loaicongvan = array();
$loaicongvan_list = $loaicongvan->get_list_condition(array('id_parent' => new MongoId($id)));

if($loaicongvan_list){
	foreach($loaicongvan_list as $lcv){
		array_push($arr_loaicongvan, new MongoId($lcv['_id']));
	}
}
$condition_1 = array('id_loaicongvan' => array('$in' => $arr_loaicongvan));
$condition_2 = array('$or' => array(array('socongvan' => new MongoRegex('/' . $keysearch . '/i')), array('trichyeu' => new MongoRegex('/' .$keysearch. '/i'))));
$condition = array('$and' => array($condition_1, $condition_2));

$congvan = new CongVan();
$congvan_list = $congvan->get_list_to_position_condition($condition, $start, $length);
$recordsTotal = $congvan->count_all();
$recordsFiltered = $congvan->get_totalFilter($condition);
$arr_congvan = array();

if(isset($congvan_list) && $congvan_list){
	$i= $recordsTotal - $start;
	foreach ($congvan_list as $cv) {
		$socongvan = $cv['socongvan'] ? $cv['socongvan'] : '';
		$sothutu = isset($cv['sothutu']) ? $cv['sothutu'] : '';
		$trichyeu = $cv['trichyeu'] ? '<a href="chitietcongvan.php?id='.$cv['_id'].'">'. $cv['trichyeu'] . '</a>' : '';
		$ngayky = $cv['ngayky'] ? date("d/m/Y",$cv['ngayky']->sec) : '';
		$ngaydiden = $cv['ngaydiden'] ? date("d/m/Y",$cv['ngaydiden']->sec) : '';

		array_push($arr_congvan, array(
				$i,$sothutu, $socongvan, $trichyeu, $ngayky, $ngaydiden,
				'<a href="suacongvan.php?id='.$cv['_id'].'"><span class="mif-pencil"></span></a>',
				'<a href="xoacongvan.php?id='.$cv['_id'].'" onclick="return confirm(\'Bạn chắc chắn xoá?\');"><span class="mif-bin"></span></a>'));
		$i--;
	}
}
echo json_encode(
  array('draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $arr_congvan
    ));
?>
<?php
require_once('header.php');
$congvan = new CongVan();$loaivanban = new LoaiVanBan();
$id = isset($_GET['id']) ? $_GET['id'] : '';

$congvan->id = $id;
$cv = $congvan->get_one();
if($congvan->delete()){
	if($cv['dinhkem']){
		foreach ($cv['dinhkem'] as $key => $value) {
			if(file_exists($target_files . $value['alias_name'])){
				@unlink($target_files . $value['alias_name']);
			}
		}
	}
	$loaivanban->id = $cv['id_loaicongvan']; $lvb = $loaivanban->get_one();
	if(isset($lvb['id_parent']) && $lvb['id_parent'] && $lvb['id_parent'] == '558a4883020ade300d0008ff'){
		transfers_to('danhsachcongvandi.php');
	} else {
		transfers_to('danhsachcongvanden.php');
	}
	//transfers_to('danhsachcongvan.php');
} else {
	echo 'Không thể xoá';
}
?>

<?php require_once('footer.php'); ?>
<?php
require_once('header.php');
$congvan = new CongVan(); $loaivanban = new LoaiVanBan();
$id_loaivanban = isset($_GET['id_loaivanban']) ? $_GET['id_loaivanban'] : '';
if(isset($_GET['submit']) && $id_loaivanban){
	$arr_loaivanban = array();
	$lvb_list = $loaivanban->get_list_condition(array('id_parent' => new MongoId($id_loaivanban)));
	if($lvb_list && $lvb_list->count() > 0){
		foreach ($lvb_list as $vb) {
			array_push($arr_loaivanban, new MongoId($vb['_id']));
		}
	} else {
		array_push($arr_loaivanban, new MongoId($id_loaivanban));
	}
	$congvan_list = $congvan->get_list_condition(array('id_loaicongvan' => array('$in' => $arr_loaivanban)));
}
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		$("#congvan_list").dataTable({
			"columns": [null,  null, null, null, null, null, null ]
		});
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Tìm theo loại Công văn</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" id="timkiemtheoloaicongvanform">
	Chọn loại văn bản: <select name="id_loaivanban" id="id_loaivanban" class="select2" style="width:200px;">
		<option value="">Loại văn bản</option>
		<?php
		$loaivanban_list = $loaivanban->get_list_condition(array('id_parent' => ""));
		foreach ($loaivanban_list as $lvb) {
			echo '<option value="'.$lvb['_id'].'"'.($lvb['_id']==$id_loaivanban ? ' selected' : '').'>'.$lvb['ten'].'</option>';
			$child_list = $loaivanban->get_list_condition(array('id_parent' => new MongoId($lvb['_id'])));
			if($child_list){
				foreach ($child_list as $child) {
					echo '<option value="'.$child['_id'].'"'.($child['_id']==$id_loaivanban ? ' selected' : '').'>&nbsp;&nbsp;-- '.$child['ten'].'</option>';
				}
			}
			
		}
		?>
	</select>
	<button class="button primary" name="submit" value="OK" id="submit"><span class="mif-checkmark"></span> Ok</button>
	<?php if(isset($_GET['submit'])): ?>
		<a href="export_loaicongvan.php?id_loaivanban=<?php echo $id_loaivanban; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
	<?php endif; ?>
</form>
<?php if(isset($congvan_list) && $congvan_list): ?>
<?php
	$loaivanban->id = $id_loaivanban; $cv = $loaivanban->get_one();
?>
<h2 style="text-align:center;">Kết quả có <b><?php echo $congvan_list->count() . '</b> ' . $cv['ten']; ?>  </h2>
<table class="table hovered striped" id="congvan_list">
	<thead>
		<tr>
			<th class="sortable-column sort-desc">STT</th>
			<th>Số Công văn</th>
			<th>Trích yếu</th>
			<th>Ngày ký</th>
			<th>Ngày đi/đến</th>
			<th><span class="mif-pencil"></span></th>
			<th><span class="mif-bin"></span></th>
		</tr>
	</thead>
	<tbody>
	<?php
	//$i = $congvan_list->count();
	foreach ($congvan_list as $cv) {
		$stt = isset($cv['sothutu']) ? $cv['sothutu']: '0';
		echo '<tr>';
		echo '<td>'.$stt.'</td>';
		echo '<td>'.($cv['socongvan'] ? $cv['socongvan'] : '').'</td>';
		echo '<td><a href="chitietcongvan.php?id='.$cv['_id'].'">'.($cv['trichyeu'] ? $cv['trichyeu'] : '').'</a></td>';
		echo '<td>'.($cv['ngayky'] ? date("d/m/Y",$cv['ngayky']->sec) : '').'</td>';
		echo '<td>'.($cv['ngaydiden'] ? date("d/m/Y",$cv['ngaydiden']->sec) : '').'</td>';
		echo '<td><a href="suacongvan.php?id='.$cv['_id'].'"><span class="mif-pencil"></span></a></td>';
		echo '<td><a href="xoacongvan.php?id='.$cv['_id'].'" onclick="return confirm(\'Bạn chắc chắn xoá?\');"><span class="mif-bin"></span></a></th>';
		echo '</tr>';
		//$i--;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
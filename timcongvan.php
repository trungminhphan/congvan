<?php
require_once('header.php');
$congvan = new CongVan();$donvisoanthao = new DonViSoanThao();
$query = array();$query_submit = array();$id_donvisoanthao = '';
if(isset($_GET['submit'])){
	$keysearch = isset($_GET['keysearch']) ? $_GET['keysearch'] : '';
	$id_donvisoanthao = isset($_GET['id_donvisoanthao']) ? $_GET['id_donvisoanthao'] : '';
	if($keysearch){
		array_push($query, array('$or' => array(array('socongvan' => new MongoRegex('/' .$keysearch. '/i')), array('trichyeu' => new MongoRegex('/' .$keysearch. '/i')))));
	}
	if($id_donvisoanthao){
	 	array_push($query,	array('id_donvisoanthao' => new MongoId($id_donvisoanthao)));
	}
	if($keysearch && $id_donvisoanthao){
		$query_submit = array('$and' => $query);
	} else if($keysearch && !$id_donvisoanthao){
		$query_submit = array('$or' => array(array('socongvan' => new MongoRegex('/' .$keysearch. '/i')), array('trichyeu' => new MongoRegex('/' .$keysearch. '/i'))));
	} else if(!$keysearch && $id_donvisoanthao){
		$query_submit = array('id_donvisoanthao' => new MongoId($id_donvisoanthao));
	}
	//$query = array('trichyeu' => new MongoRegex('/' .$keysearch. '/i'));
	$congvan_list = $congvan->get_list_condition($query_submit);
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		$("#congvan_list").dataTable({
			"columns" : [null, null, null, null, null, null, null ]
		});
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Tìm kiếm</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" id="timkiemform">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan4 input-control text">
		    <input type="text" name="keysearch" id="keysearch" placeholder="Từ khoá tìm kiếm" value="<?php echo isset($keysearch) ? $keysearch : ''; ?>" />
		</div>
		<div class="cell colspan4 input-control select" placeholder="Đơn vị soạn thảo">
			<select name="id_donvisoanthao" id="id_donvisoanthao" class="select2">
				<option value="">Đơn vị soạn thảo</option>
				<?php 
					$donvisoanthao_list = $donvisoanthao->get_all_list();
					if($donvisoanthao_list){
						foreach ($donvisoanthao_list as $st) {
							echo '<option value="'.$st['_id'].'"'.($st['_id'] == $id_donvisoanthao ? ' selected' :'').'>'.$st['ten'].'</option>';
						}
					}
				?>
			</select>
		</div>
		<div class="cell colspan2">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-search"></span></button>
		</div>
	</div>
</div>
</form>
<?php if(isset($congvan_list) && $congvan_list): ?>
<table class="table hovered striped dataTable" id="congvan_list">
	<thead>
		<tr>
			<th>STT</th>
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
	$i = 1;
	foreach ($congvan_list as $cv) {
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td>'.$cv['socongvan'].'</td>';
		echo '<td><a href="chitietcongvan.php?id='.$cv['_id'].'">'.$cv['trichyeu'].'</a></td>';
		echo '<td>'.($cv['ngayky'] ? date("d/m/Y",$cv['ngayky']->sec) : '').'</td>';
		echo '<td>'.($cv['ngaydiden'] ? date("d/m/Y",$cv['ngaydiden']->sec) : '').'</td>';
		echo '<td><a href="suacongvan.php?id='.$cv['_id'].'"><span class="mif-pencil"></span></a></td>';
		echo '<td><a href="xoacongvan.php?id='.$cv['_id'].'" onclick="return confirm(\'Bạn chắc chắn xoá?\');"><span class="mif-bin"></span></a></th>';
		echo '</tr>';
		$i++;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>

<?php
require_once('header.php');
$congvan = new CongVan();$donvisoanthao = new DonViSoanThao();
$id_donvisoanthao='';
if(isset($_POST['submit'])){
	$arr_query = array();
	$public = 1;
	$socongvan = isset($_POST['socongvan']) ? $_POST['socongvan'] : '';
	$trichyeu = isset($_POST['trichyeu']) ? $_POST['trichyeu'] : '';
	$id_donvisoanthao = isset($_POST['id_donvisoanthao']) ? $_POST['id_donvisoanthao'] : '';

	if($socongvan || $trichyeu || $id_donvisoanthao){
		if($socongvan){
			array_push($arr_query, array('socongvan' => new MongoRegex('/' .$socongvan . '/i')));
		}
		if($trichyeu){
			array_push($arr_query, array('trichyeu' => new MongoRegex('/i' .$trichyeu . '/i')));
		}
		if($id_donvisoanthao){
			array_push($arr_query, array('id_donvisoanthao' => new MongoId($id_donvisoanthao)));
		}
		$query = array('public' => 1, '$or' => $arr_query);
		$congvan_list = $congvan->get_public_list_condition($query);
	} else {
		$congvan_list = $congvan->get_public_list();	
	}
} else {
	$congvan_list = $congvan->get_public_list();
}
$donvisoanthao_list = $donvisoanthao->get_all_list();
?>
<script type="text/javascript" src="../js/select2.min.js"></script>
<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
	});
</script>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="searchform">
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan12"><h4><span class="mif-search"></span> Tìm Công văn</h4></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 input-control text">
			<input type="text" name="socongvan" id="socongvan" value="<?php echo isset($socongvan) ? $socongvan : ''; ?>" placeholder="Số công văn" />
		</div>
		<div class="cell colspan5 input-control text">
			<input type="text" name="trichyeu" id="trichyeu" value="<?php echo isset($trichyeu) ? $trichyeu : ''; ?>" placeholder="Trích yếu" />			
		</div>
		<div class="cell colspan5 input-control" data-role="select" data-placeholder="Đơn vị soạn thảo" data-allow-clear="true">
			<select name="id_donvisoanthao" id="id_donvisoanthao" class="select2">
				<option value="">Đơn vị soạn thảo</option>
				<?php
				if($donvisoanthao_list){
					foreach ($donvisoanthao_list as $dvst) {
						echo '<option value="'.$dvst['_id'].'"'.($dvst['_id']==$id_donvisoanthao?' selected' : '').'>'.$dvst['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
        <div class="cell colspan12 padding10 align-center">
            <button class="button primary" name="submit" value="OK" id="submit"><span class="mif-checkmark"></span> Tìm kiếm</button>
        </div>
    </div>
</div>
</form>
<h3><span class="mif-list2"></span> Danh sách công văn.</h3>
<?php if(isset($congvan_list) && $congvan_list) : ?>
<table class="table striped hovered dataTable" id="congvan_list">
<thead>
	<tr>
		<th>STT</th>
		<th>Số Công văn</th>
		<th>Trích yếu</th>
		<th>Ngày ký</th>
		<th>Ngày đến/đi</th>
	</tr>
</thead>
<tbody>
	<?php
	$i=1;
	foreach ($congvan_list as $cv) {
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td><a href="chitietcongvan.php?id='.$cv['_id'].'">'.$cv['socongvan'].'</a></td>';
		echo '<td>'.$cv['trichyeu'].'</td>';
		echo '<td>'.($cv['ngayky'] ? date("d/m/Y", $cv['ngayky']->sec) : '').'</td>';
		echo '<td>'.($cv['ngaydiden'] ? date("d/m/Y", $cv['ngaydiden']->sec) : '').'</td>';
		echo '</tr>';
		$i++;
	}
	?>
</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
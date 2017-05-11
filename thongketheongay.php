<?php require_once('header.php');
$congvan = new CongVan();$loaicongvan = new LoaiVanBan();
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn sai ngày....';
	} else {
		$date1 = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$date2 = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		//$query = array(array('ngaydiden' => array('$gte' => $date1), 'ngaydiden' => array('$lte' => $date2)));
		//$query = array('ngaydiden' => array('$gte' => $date1));
		//$query = array('ngaydiden' => array('$lte' => $date2));
		$query = array('$and' => array(array('ngaydiden' => array('$gte' => $date1)), array('ngaydiden' => array('$lte' => $date2))));
		$list = $congvan->get_list_condition($query);
	}
}
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".ngaythangnam").inputmask();
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thống kê theo ngày</h1>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="GET" id="thongkeform" data-role="validator" data-show-required-state="false">
<div class="grid example">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10 align-right">Từ ngày</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="tungay" id="tungay" placeholder="Từ ngày" data-inputmask="'alias': 'date'" class="ngaythangnam" data-validate-func="required" value="<?php echo isset($tungay) ? $tungay : date("d/m/Y"); ?>" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2 padding-top-10 align-right">Đến ngày</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="denngay" id="denngay" placeholder="Đến ngày" data-inputmask="'alias': 'date'" class="ngaythangnam" data-validate-func="required" value="<?php echo isset($denngay) ? $denngay : date("d/m/Y", strtotime("+7 day", strtotime(date("Y-m-d")))); ?>"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
        <div class="cell colspan12 padding10 align-center">
            <button class="button primary" name="submit" value="OK" id="submit"><span class="mif-checkmark"></span> Xem thống kê</button>
            <?php if(isset($_GET['submit'])): ?>
		<a href="export_thongketheongay.php?<?php echo $_SERVER['QUERY_STRING']; ?>" class="button success"><span class="mif-file-excel"></span> Excel</a>
	<?php endif; ?>
        </div>
    </div>
</div>
</form>
<hr />
<?php if(isset($list) && $list) : ?>
<table class="table striped border bordered hovered dataTable" data-role="dataTable">
	<thead>
		<tr>
			<th>STT</th>
			<th>Số công văn</th>
			<th>Trích yếu</th>
			<th>Ngày ký</th>
			<th>Ngày đi/đến</th>
			<th width="140">Loại công văn</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach ($list as $l) {
		$ngayky = (isset($l['ngayky']) && $l['ngayky']) ? date("d/m/Y", $l['ngayky']->sec) : '';
		$ngaydiden = isset($l['ngaydiden']) ? date("d/m/Y", $l['ngaydiden']->sec) : '';
		if(isset($l['id_loaicongvan']) && $l['id_loaicongvan']){
			$loaicongvan->id = $l['id_loaicongvan']; $lcv = $loaicongvan->get_one();
			$loaicongvan->id_parent = $lcv['id_parent']; $p = $loaicongvan->get_parent_name();
		} else {
			 $p = '';
		}

		echo '<tr>
			<td>'.$i.'</td>
			<td>'.$l['socongvan'].'</td>
			<td><a href="chitietcongvan.php?id='.$l['_id'].'" target="_blank">'.$l['trichyeu'].'</td>
			<td>'.$ngayky.'</td>
			<td>'.$ngaydiden.'</td>
			<td>'.($p ? $p : '').'</td>
		</tr>';$i++;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>


<?php
require_once('header.php');
$id = isset($_GET['id']) ? $_GET['id']: '';
$congvan = new CongVan();$loaivanban = new LoaiVanBan();
$donvisoanthao = new DonViSoanThao();$linhvuc = new LinhVuc();
$congvan->id = $id; $cv = $congvan->get_one_public();
?>
<script type="text/javascript" src="../js/select2.min.js"></script>
<script type="text/javascript">
	$(".select2").select2();
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết công văn</h1>
<table class="table striped hovered border bordered">
<tbody>
	<tr>
		<td>Loại công văn</td>
		<td>
			<?php 
			if(isset($cv['id_loaicongvan']) && $cv['id_loaicongvan']){
				$loaivanban->id = $cv['id_loaicongvan']; $lcv = $loaivanban->get_one();
				echo $lcv['ten'] ? $lcv['ten'] : ''; 
			} else {
				echo '';
			} ?>
		</td>
	</tr>	
	<tr>
		<td>Đơn vị soạn thảo</td>
		<td>
			<?php
			if(isset($cv['id_donvisoanthao'])){
				$donvisoanthao->id = $cv['id_donvisoanthao']; $dv = $donvisoanthao->get_one();
				echo $dv['ten'] ?  $dv['ten'] : '';
			} else {
				echo '';
			}
			?>

		</td>
	</tr>
	<tr>
		<td>Lĩnh vực</td>
		<td>
			<?php 
			if(isset($cv['id_linhvuc']) && $cv['id_linhvuc']){
				$linhvuc->id = $cv['id_linhvuc']; $lv = $linhvuc->get_one();
				echo $lv['ten'] ? $lv['ten'] : '';
			} else {echo ''; }?>
		</td>
	</tr>
	<tr>
		<td>Trích yếu</td>
		<td>
			<?php echo $cv['trichyeu']; ?>
		</td>
	</tr>
	<tr>
		<td>Số công văn</td>
		<td>
			<?php echo $cv['socongvan']; ?>
		</td>
	</tr>
	<tr>
		<td>Ngày ký</td>
		<td>
			<?php echo $cv['ngayky'] ? date("d/m/Y", $cv['ngayky']->sec) : ''; ?>
		</td>
	</tr>
	<tr>
		<td>Ngày đi/Ngày đến</td>
		<td>
			<?php echo $cv['ngaydiden'] ? date("d/m/Y", $cv['ngaydiden']->sec) : ''; ?>
		</td>
	</tr>
	<tr>
		<td>Thời hạn báo cáo</td>
		<td>
			<?php echo $cv['thoihanbaocao'] ? date("d/m/Y", $cv['thoihanbaocao']->sec) : ''; ?>
		</td>
	</tr>
	<tr>
		<td>Cán bộ báo cáo</td>
		<td>
			<?php echo $cv['canbobaocao'] ? $cv['canbobaocao'] : ''; ?>
		</td>
	</tr>
	<tr>
		<td>Các văn bản liên quan</td>
		<td>
			<?php
			if($cv['cacvanbancolienquan']){
				echo '<ul class="simple-list">';
				foreach ($cv['cacvanbancolienquan'] as $key => $value) {
					$congvan->id = $value; $lq = $congvan->get_vanbanlienquan();
					echo  '<li><a href="chitietcongvan.php?id='.$lq['_id'].'">' . $lq['socongvan'] . ' - ' . $lq['trichyeu'] . '</a></li>';
				}
				echo '</ul>';
			}
			?>
		</td>
	</tr>
	<tr>
		<td>Ghi chú</td>
		<td>
			<?php echo $cv['ghichu'] ? $cv['ghichu'] : ''; ?>
		</td>
	</tr>
	<tr>
		<td>Đính kèm</td>
		<td>
			<?php
			if($cv['dinhkem']){
				echo '<ul class="simple-list">';
				foreach ($cv['dinhkem'] as $key => $value) {
					echo '<li><a href="../'.$target_files . $value['alias_name'].'">'.$value['filename'].'</a></li>';
				}
				echo '</ul>';
			}
			?>
		</td>
	</tr>
</tbody>
</table>

<?php require_once('footer.php'); ?>
<?php
require_once('header.php');
$id = isset($_GET['id']) ? $_GET['id']: '';
$congvan = new CongVan();$loaivanban = new LoaiVanBan();
$donvisoanthao = new DonViSoanThao();$linhvuc = new LinhVuc();
$emailaccount = new EmailAccount();
$congvan->id = $id; $cv = $congvan->get_one();
//id_congvandi = '558a4883020ade300d0008ff'
//id_congvanden = '558a4871020adebc0e0062b0'

$loaivanban->id = $cv['id_loaicongvan'];$lvb = $loaivanban->get_one();
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
	$(".select2").select2();
</script>
<?php if(isset($lvb['id_parent']) && $lvb['id_parent'] && $lvb['id_parent'] == '558a4883020ade300d0008ff'): ?>
	<h1><a href="danhsachcongvandi.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết công văn</h1>
<?php else: ?>
	<h1><a href="danhsachcongvanden.php" class="nav-button transform"><span></span></a>&nbsp;Chi tiết công văn</h1>
<?php endif; ?>

<a href="suacongvan.php?id=<?php echo $id; ?>" class="button primary place-right"><span class="mif-pencil"></span> Sửa</a>
<table class="table striped hovered border bordered">
<tbody>
	<tr>
		<td>Loại công văn</td>
		<td>
			<?php 
			if(isset($cv['id_loaicongvan']) && $cv['id_loaicongvan']){
				$loaivanban->id = $cv['id_loaicongvan']; $lcv = $loaivanban->get_one();
				$loaivanban->id_parent = $lcv['id_parent']; $p = $loaivanban->get_parent_name();
				echo isset($p) ?  '<b>[' . $p . ']</b>&nbsp;&nbsp;&nbsp;' : '';
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
			if(isset($cv['id_donvisoanthao']) && $cv['id_donvisoanthao']){
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
					echo '<li><a href="'.$target_files . $value['alias_name'].'">'.$value['filename'].'</a></li>';
				}
				echo '</ul>';
			}
			?>
		</td>
	</tr>
</tbody>
</table>
<h2><span class="mif-envelop mif-ani-shake"></span> Các email đã gởi:</h2>
<?php
$arr_emaillist = array();
if(isset($cv['emaillist']) && $cv['emaillist']){
	foreach ($cv['emaillist'] as $m) {
		$emailaccount->id = $m['id_emailaccount']; $ea = $emailaccount->get_one();
		if($m['view'] == 0){
			echo '<span class="tag alert">';
		} else { echo '<span class="tag success">'; }
		echo '<b>'.$ea['ghichu'] . '</b> '. $ea['emailaddress'] . ' [' .$m['view'] . ']';
		echo '</span> &nbsp;';
		array_push($arr_emaillist, $m['id_emailaccount']);
	}
}

$condition = array('_id' => array('$nin' => $arr_emaillist));
$emaillist = $emailaccount->get_list_condition($condition);
?>
<form action="send_email.php" method="POST" id="sendEmail" data-role="validator" data-show-required-state="false">
	<input type="hidden" name="id_congvan" id="id_congvan" value="<?php echo $id; ?>" />
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan12 input-control textarea">
			<textarea name="kinhgui" id="kinhgui" placeholder="Kính gửi" data-validate-func="required"><?php echo isset($kinhgui) ? $kinhgui : '';?></textarea>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 input-control select" data-role="select" data-placeholder="Chọn địa chỉ Email">
			<select name="id_emailaccount[]" class="select2" multiple>
				<?php
				if($emaillist){
					foreach ($emaillist as $e) {
						echo '<option value="'.$e['_id'].'">'.$e['emailaddress'].'</option>';
					}
				}
				?>
			</select>
			<span class="tag success">Đã đọc email</span><span class="tag alert">Chưa đọc email</span>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button class="button primary" name="submit" value="Send" id="submit"><span class="mif-envelop mif-ani-shake"></span> Send email</button>
		</div>
	</div>
</div>
</form>
<?php require_once('footer.php'); ?>
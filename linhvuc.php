<?php 
require_once('header.php');
$linhvuc = new LinhVuc();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
if($act=='del' && $id){
	$linhvuc->id = $id;
	if($linhvuc->delete()){
		transfers_to('linhvuc.php');	
	} 
	else $msg = 'Không thể xoá';
}

if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
	$linhvuc->id = $id; $linhvuc->ten = $ten;
	if($id){
		if($linhvuc->edit()){
			transfers_to('linhvuc.php');
		} else {
			$msg = 'Không thể xoá';
		}
	} else {
		if($linhvuc->insert()){
			transfers_to('linhvuc.php');
		} else {
			$msg = 'Không thể thêm';
		}	
	}
}
if($id){
	$linhvuc->id = $id; $cv = $linhvuc->get_one();
	$ten = $cv['ten'];
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
		
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Lĩnh vực</h1>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="linhvucform" data-role="validator" data-show-required-state="false">
	<a href="loaivanban.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" placeholder="Tên lĩnh vực" data-validate-func="required" data-validate-hint="Hãy nhập tên lĩnh vực!" data-validate-hint-position="bottom"/>
		<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>

	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<table class="table bordered hovered striped">
<thead>
    <tr>
    	<th>STT</th>
        <th>Lĩnh vực</th>
        <th><span class="mif-bin"></th>
        <th><span class="mif-pencil"></span></th>
    </tr>
</thead>
<?php
$linhvuc_list = $linhvuc->get_all_list();
if($linhvuc_list){
	$i = 1;
	foreach ($linhvuc_list as $lv){
		echo '<tr>';
		echo '<td>'.$i.'</td>';
		echo '<td><span class="mif-folder-open"></span> '.$lv['ten'].'</td>';
		echo '<td><a href="linhvuc.php?id='.$lv['_id'].'&act=del" onclick="return confirm(\'Chắc chắn xoá? \n Nếu xoá sẽ mất tất cả mục con!\');"><span class="mif-bin"></span></a></td>';
		echo '<td><a href="linhvuc.php?id='.$lv['_id'].'"><span class="mif-pencil"></span></a></td>';
		echo '</tr>';$i++;
	}
}
?>
 </table>

<?php require_once('footer.php'); ?>
<?php 
require_once('header.php');
$loaivanban = new LoaiVanBan();
$parent_list = $loaivanban->get_list_condition(array('id_parent' => ''));
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
if($act=='del' && $id){
	$loaivanban->id = $id;
	if($loaivanban->delete()){
		$loaivanban->id_parent = $id;
		$loaivanban->delete_childs();
		transfers_to('loaivanban.php');	
	} 
	else $msg = 'Không thể xoá';
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
	$id_parent = isset($_POST['id_parent']) ? $_POST['id_parent'] : '';
	$loaivanban->id = $id; $loaivanban->ten = $ten; $loaivanban->id_parent = $id_parent;
	if($id){
		if($loaivanban->edit()){
			transfers_to('loaivanban.php');
		} else {
			$msg = 'Không thể xoá';
		}
	} else {
		if($loaivanban->insert()){
			transfers_to('loaivanban.php');
		} else {
			$msg = 'Không thể thêm';
		}	
	}
}
if($id){
	$loaivanban->id = $id; $cv = $loaivanban->get_one();
	$ten = $cv['ten']; $id_parent = $cv['id_parent'];
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Loại Văn bản</h1>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="loaivanbanform" data-role="validator" data-show-required-state="false">
	<a href="loaivanban.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
	<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
	<div class="input-control text">
		<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" placeholder="Tên loại văn bản" data-validate-func="required" data-validate-hint="Hãy nhập tên loại văn bản!" data-validate-hint-position="bottom" />
		<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
	</div>
	<div class="input-control select">
		<select name="id_parent" id="id_parent" class="select2">
			<option value="">Cấp trên</option>
			<?php
			if($parent_list){
				foreach ($parent_list  as $p) {
					echo '<option value="'.$p['_id'].'"'.($p['_id'] == $id_parent ? ' selected' : '').'>'.$p['ten'].'</option>';
				}
			}
			?>
		</select>
	</div>
	<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
</form>
</div>
<table class="table bordered hovered">
<thead>
    <tr>
    	<th>STT</th>
        <th>Tên văn bản</th>
        <th><span class="mif-bin"></th>
        <th><span class="mif-pencil"></span></th>
    </tr>
</thead>
<?php
if($parent_list){
	foreach ($parent_list as $p){
		echo '<tr style="background:#0072c6;" class="fg-white">';
		echo '<td colspan="2"><span class="mif-folder-open"></span> '.$p['ten'].'</td>';
		//echo '<td><a href="loaivanban.php?id='.$p['_id'].'&act=del" onclick="return confirm(\'Chắc chắn xoá? \n Nếu xoá sẽ mất tất cả mục con!\');"><span class="mif-bin fg-white"></span></a></td>';
		//echo '<td><a href="loaivanban.php?id='.$p['_id'].'"><span class="mif-pencil fg-white"></span></a></td>';
		echo '<td></td><td></td>';
		echo '</tr>';
		$childs = $loaivanban->get_list_condition(array('id_parent' => new MongoId($p['_id'])));
		if($childs){
			$i = 1;
			foreach ($childs as $child) {
				echo '<tr>';
				echo '<td>'.$i.'</td>';
				echo '<td><span class="mif-files-empty"></span> '.$child['ten'].'</td>';
				echo '<td><a href="loaivanban.php?id='.$child['_id'].'&act=del" onclick="return confirm(\'Chắc chắn xoá?\');"><span class="mif-bin"></span></a></td>';
				echo '<td><a href="loaivanban.php?id='.$child['_id'].'"><span class="mif-pencil"></a></td>';
				echo '</tr>';$i++;
			}
		}
	}
}
?>
 </table>

<?php require_once('footer.php'); ?>
<?php 
require_once('header.php');
$donvisoanthao = new DonViSoanThao();
$donvisoanthao_list = $donvisoanthao->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : '';
if($update == 'ok') $msg = 'Cập nhật thành công';
if($act=='del' && $id){
	$donvisoanthao->id = $id;
	if($donvisoanthao->delete()){
		transfers_to('donvisoanthao.php?update=ok');	
	}
	else $msg = 'Không thể xoá';
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$ten = isset($_POST['ten']) ? $_POST['ten'] : '';
	$donvisoanthao->id = $id; $donvisoanthao->ten = trim($ten);
	if($id){
		if($donvisoanthao->edit()){
			transfers_to('donvisoanthao.php');
		} else {
			$msg = 'Không thể xoá';
		}
	} else {
		//insert
		if($donvisoanthao->check_exists()){
			$msg = 'Tên đơn vị soạn thảo đã tồn tại';
		} else {
			if($donvisoanthao->insert()){
				transfers_to('donvisoanthao.php');
			} else {
				$msg = 'Không thể thêm';
			}
		}
	}
}
if($id){
	$donvisoanthao->id = $id; $cv = $donvisoanthao->get_one();$ten = $cv['ten'];
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
		
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý đơn vị soạn thảo văn bản</h1>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="donvisoanthaoform" data-role="validator" data-show-required-state="false">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 align-right">
			<a href="donvisoanthao.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
		</div>
		<div class="cell colspan6 input-control text">
			<input type="text" name="ten" id="ten" value="<?php echo isset($ten) ? $ten : ''; ?>" placeholder="Tên đơn vị soạn thảo" data-validate-func="required" data-validate-hint="Hãy nhập tên mục chi ngân sách!" data-validate-hint-position="bottom"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan2">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-checkmark"></span> Cập nhật</button>
		</div>
	</div>
</div>
</form>
</div>
<table class="table striped hovered dataTable" data-role="datatable">
<thead>
    <tr>
    	<th>STT</th>
        <th>Tên đơn vị</th>
        <th><span class="mif-bin"></th>
        <th><span class="mif-pencil"></span></th>
    </tr>
</thead>
<?php
if($donvisoanthao_list){
	$i= 1;
	foreach ($donvisoanthao_list as $p){
		echo '<tr>';
		echo '<td><b>'.$i.'</b></td>';
		echo '<td>'.$p['ten'].'</td>';
		echo '<td><a href="donvisoanthao.php?id='.$p['_id'].'&act=del" onclick="return confirm(\'Chắc chắn xoá? \n Nếu xoá sẽ mất tất cả mục con!\');"><span class="mif-bin"></span></a></td>';
		echo '<td><a href="donvisoanthao.php?id='.$p['_id'].'"><span class="mif-pencil"></span></a></td>';
		echo '</tr>';$i++;
	}
}
?>
 </table>

<?php require_once('footer.php'); ?>
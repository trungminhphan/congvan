<?php 
require_once('header.php');
$emailaccount = new EmailAccount();$congvan = new CongVan();$emailgroup = new EmailGroup();
$email_list = $emailaccount->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$update = isset($_GET['update']) ? $_GET['update'] : '';
if($update == 'ok') $msg = 'Cập nhật thành công';
if($act=='del' && $id){
	$emailaccount->id = $id;
	if($congvan->check_email($id) || $emailgroup->check_dm_email($id)){
		$msg = 'Không thể xoá [Công văn], [Email Group] ';
	} else{
		if($emailaccount->delete()){
			transfers_to('emailaccount.php?update=ok');	
		}
		else $msg = 'Không thể xoá';
	}
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$emailaddress = isset($_POST['emailaddress']) ? $_POST['emailaddress'] : '';
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';
	$emailaccount->id = $id; $emailaccount->emailaddress = trim($emailaddress);
	$emailaccount->ghichu = $ghichu;
	if($id){
		if($emailaccount->edit()){
			transfers_to('emailaccount.php');
		} else {
			$msg = 'Không thể xoá';
		}
	} else {
		//insert
		if($emailaccount->check_exists()){
			$msg = 'Tên đơn vị soạn thảo đã tồn tại';
		} else {
			if($emailaccount->insert()){
				transfers_to('emailaccount.php');
			} else {
				$msg = 'Không thể thêm';
			}
		}
	}
}
if($id){
	$emailaccount->id = $id; $e = $emailaccount->get_one();
	$emailaddress = $e['emailaddress'];
	$ghichu = $e['ghichu'];
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
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý danh sách Email</h1>
<div style="padding:5px;">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="donvisoanthaoform" data-role="validator" data-show-required-state="false">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 align-right">
			<a href="emailaccount.php" class="button"><span class="mif-sync-problem"></span> Tải lại trang</a>
			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : '';  ?>" />
		</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="emailaddress" id="emailaddress" value="<?php echo isset($emailaddress) ? $emailaddress : ''; ?>" placeholder="Địa chỉ Email" data-validate-func="required" data-validate-hint="Địa chỉ email!" data-validate-hint-position="bottom"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="ghichu" id="ghichu" value="<?php echo isset($ghichu) ? $ghichu : ''; ?>" placeholder="Ghi chú" />
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
        <th>Email</th>
        <th>Ghi chu</th>
        <th><span class="mif-bin"></th>
        <th><span class="mif-pencil"></span></th>
    </tr>
</thead>
<?php
if($email_list){
	$i= 1;
	foreach ($email_list as $e){
		echo '<tr>';
		echo '<td><b>'.$i.'</b></td>';
		echo '<td>'.$e['emailaddress'].'</td>';
		echo '<td>'.$e['ghichu'].'</td>';
		echo '<td><a href="emailaccount.php?id='.$e['_id'].'&act=del" onclick="return confirm(\'Chắc chắn xoá? \n Nếu xoá sẽ mất tất cả mục con!\');"><span class="mif-bin"></span></a></td>';
		echo '<td><a href="emailaccount.php?id='.$e['_id'].'"><span class="mif-pencil"></span></a></td>';
		echo '</tr>';$i++;
	}
}
?>
 </table>

<?php require_once('footer.php'); ?>
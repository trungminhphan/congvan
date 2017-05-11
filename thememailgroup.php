<?php
require_once('header.php');
$emailgroup = new EmailGroup();
$emailaccount = new EmailAccount();
$email_list = $emailaccount->get_all_list();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$act = isset($_GET['act']) ? $_GET['act'] : '';
$id_group = ''; $arr_email=array();
if($id && $act=='del'){
	$emailgroup->id = $id;
	if($emailgroup->delete()) transfers_to('emailgroup.php?msg=Xoá thành công');
	else $msg = 'Không thể xoá';
}
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$act = isset($_POST['act']) ? $_POST['act'] : '';
	$id_group = isset($_POST['id_group']) ? $_POST['id_group'] : '';
	$arr_email = isset($_POST['id_email']) ? $_POST['id_email'] : '';
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';

	$emailgroup->id_group = $id_group;
	$emailgroup->id_email = $arr_email;
	$emailgroup->ghichu = $ghichu;

	if($id && $act=='edit'){
		//edit
		$emailgroup->id = $id;
		if($emailgroup->check_exists()){
			if($emailgroup->edit_except_group()) transfers_to('emailgroup.php?msg=Chỉnh sửa thành công');
			else $msg = 'Không thể chỉnh sửa';
		} else {
			if($emailgroup->edit()) transfers_to('emailgroup.php?msg=Chỉnh sửa thành công');
			else $msg = 'Không thể chỉnh sửa';
		}
	} else {
		//insert
		if($emailgroup->check_exists()){
			$msg = 'Nhóm email đã có trong CSDL';
		} else {
			if($emailgroup->insert()) transfers_to('emailgroup.php?msg=Thêm thành công');
			else $msg = 'Không thể thêm mới';
		}

	}
}
if($id && $act=='edit'){
	$emailgroup->id = $id; $g = $emailgroup->get_one();
	$id_group = $g['id_group'];
	$arr_email = $g['id_email'];
	$ghichu = $g['ghichu'];
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();
		$("#id_email").select2({
			placeholder: "Chọn Email trong nhóm"
		});
		$("select").on("select2:select", function (evt) {
			var element = evt.params.data.element; var $element = $(element);
			$element.detach(); $(this).append($element); $(this).trigger("change");
		});
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
	});
</script>
<h1><a href="emailgroup.php" class="nav-button transform"><span></span></a>&nbsp;Thông tin nhóm Email (Group mail)</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="emailgroupform">
<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>">
<input type="hidden" name="act" id="act" value="<?php echo isset($act) ? $act : ''; ?>">
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Nhóm (Group)</div>
		<div class="cell colspan10 input-control select">
			<select name="id_group" id="id_group" class="select2">
			<?php
			if($email_list){
				foreach($email_list as $e){
					$g = $e['ghichu'] ? ' (' . $e['ghichu'] . ')' : '';
					echo '<option value="'.$e['_id'].'"'.($e['_id'] == $id_group ? ' selected' :'').'>'.$e['emailaddress'] .$g.'</option>';
				}
			}
			?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Email</div>
		<div class="cell colspan10 input-control select" style="height:100%;position:relative;">
			<select name="id_email[]" id="id_email" multiple>
			<?php
			if($email_list){
				foreach($email_list as $e){
					$g = $e['ghichu'] ? ' (' . $e['ghichu'] . ')' : '';
					echo '<option value="'.$e['_id'].'"'.(in_array($e['_id'], $arr_email) ? ' selected' : '').'>'.$e['emailaddress'] .$g.'</option>';
				}
			}
			?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Ghi chú</div>
		<div class="cell colspan10 input-control textarea">
			<textarea name="ghichu" id="ghichu"><?php echo isset($ghichu) ? $ghichu : ''; ?></textarea>
		</div>
	</div>
	<div class="row cells12">
        <div class="cell colspan12 padding10 align-center">
            <button class="button primary" name="submit" value="OK" id="submit"><span class="mif-checkmark"></span> Cập nhật</button>
            <a href="emailgroup.php" class="button" name="cancel" ><span class="mif-keyboard-return"></span> Trở về</a>
        </div>
    </div>
</div>
</form>
<?php require_once('footer.php'); ?>
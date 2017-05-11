<?php
require_once('header.php');
$id = $users->get_userid();
$password = '';
if(isset($_POST['submit'])){
	$id = isset($_POST['id']) ? $_POST['id'] : '';
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$users->id = $id;
	$users->username = $username;
	$users->password = $password;
	if($id){
		$users->reset_password();
		transfers_to('index.php');
	} 
}

if($id){
	$users->id = $id;
	$edit_user = $users->get_one();
	$id = $edit_user['_id'];
	$username = $edit_user['username'];
	$password = '';
	$roles = $edit_user['roles'];
	$person = $edit_user['person'];
}
?>
<script type="text/javascript" src="js/html5.messages.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thay đổi mật khẩu</h1>
<div class="example">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="adduserform"  data-role="validator" data-show-required-state="false" >
<div class="grid">
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Tài khoản</div>
		<div class="cell colspan6 input-control text" >
			<input type="hidden" name="id" id="id" value="<?php echo isset($id) ? $id : ''; ?>" />
			<input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>" placeholder="Nhập tài khoản" disabled class="edit-text" <?php echo $id ? 'readonly' : ''; ?>  data-validate-func="minlength" data-validate-arg="5" data-validate-hint="Tối đa 5 ký tự!"/>
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Mật khẩu</div>
		<div class="cell colspan6 input-control text" >
			<input type="password" name="password" id="password" value="" placeholder="Nhập mật khẩu" class="edit-text" data-validate-func="minlength" data-validate-arg="6" data-validate-hint="Tối đa 6 ký tự!" />
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Họ tên người được cấp tài khoản</div>
		<div class="cell colspan6 input-control text">
			<input type="text" name="person" id="person" value="<?php echo isset($person) ? $person : ''; ?>" placeholder="Nhập họ tên người được cấp" disabled class="edit-text" size="80" data-validate-func="required" data-validate-hint="Hãy nhập họ tên!">
			<span class="input-state-error mif-warning"></span><span class="input-state-success mif-checkmark"></span>
		</div>
		<div class="cell colspan4"></div>
	</div>
	<div class="row cells12">
		<div class="cell colspan12 align-center">
			<button name="submit" id="submit" value="OK" class="button primary"><span class="mif-user-check"></span> Cập nhật</button>
		</div>
	</div>
</div>
</form>
</div>
<?php require_once('footer.php'); ?>
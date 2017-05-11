<?php
require_once('header.php');
$emailgroup = new EmailGroup();
$emailaccount = new EmailAccount();
$emailgroup_list = $emailgroup->get_all_list();
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		<?php if(isset($msg) && $msg): ?>
			$.Notify({type: 'alert', caption: 'Thông báo', content: <?php echo "'".$msg."'"; ?>});
		<?php endif; ?>
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Quản lý Nhóm Email</h1>
<a href="thememailgroup.php" class="button primary"><span class="mif-plus"></span> Thêm mới</a>
<?php if($emailgroup_list) : ?>
<table class="table bordered border striped hovered dataTable" data-role="dataTable">
	<thead>
		<tr>
			<th>STT</th>
			<th>Nhóm Email</th>
			<th style="min-width:600px;">Danh sách Email</th>
			<th style="text-align:center;"><span class="mif-bin"></span></th>
			<th style="text-align:center;"><span class="mif-pencil"></span></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	foreach($emailgroup_list as $group){
		$emailaccount->id = $group['id_group']; $g = $emailaccount->get_one();
		$group_name = $g['emailaddress'];
		$email_list = '';
		if(isset($group['id_email'])){
			foreach($group['id_email'] as $email){
				$emailaccount->id = $email; $e = $emailaccount->get_one();
				$email_list .= '<span class="tag info padding5">' . $e['emailaddress'] . '</span>&nbsp;'; 
			}
		}
		echo '<tr>
				<td>'.$i.'</td>
				<td>'.$group_name.'</td>
				<td>'.$email_list.'</td>
				<td><a href="thememailgroup.php?id='.$group['_id'].'&act=del" onclick="return confirm(\'Chắc chắn xoá?\')"><span class="mif-bin"></span></a></td>
				<td><a href="thememailgroup.php?id='.$group['_id'].'&act=edit"><span class="mif-pencil"></span></a></td>
			</tr>';$i++;
	}
	?>
	</tbody>
</table>
<?php endif; ?>
<?php require_once('footer.php'); ?>
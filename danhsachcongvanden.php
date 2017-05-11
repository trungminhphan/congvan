<?php
require_once('header.php'); 
//$congvan = new CongVan();
//$congvan_list = $congvan->get_all_list();
?>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#congvan_list').dataTable( {
			"columns": [null,  null, null, null, null, null, null ],
			"processing": true,
        	"serverSide": true,
        	"ajax": "dataTable_congvan.php?id=558a4871020adebc0e0062b0",
        	"columnDefs": [
			    { "orderable": false, "targets": 0 },
			    { "orderable": false, "targets": 1 },
			    { "orderable": false, "targets": 2 },
			    { "orderable": false, "targets": 3 },
			    { "orderable": false, "targets": 4 },
			    { "orderable": false, "targets": 5 },
			    { "orderable": false, "targets": 6 },
			    { "orderable": false, "targets": 7 }
			]
		} );
	});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Danh sách công văn Đến.</h1>
<table class="table striped hovered" id="congvan_list">
	<thead>
		<tr>
			<th>STT</th>
			<th>Số TT</th>
			<th>Số Công văn</th>
			<th>Trích yếu</th>
			<th>Ngày ký</th>
			<th>Ngày đến</th>
			<th><span class="mif-pencil"></span></th>
			<th><span class="mif-bin"></span></th>
		</tr>
	</thead>
	<tbody></tbody>
</table>
<?php require_once('footer.php'); ?>
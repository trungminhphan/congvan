<?php 
require_once('header.php');
$loaivanban = new LoaiVanBan(); $linhvuc = new LinhVuc(); $donvisoanthao = new DonViSoanThao();
$congvan = new CongVan();$emailaccount = new EmailAccount();
$email_list = $emailaccount->get_all_list();
$parent_loaivanban = $loaivanban->get_list_condition(array('id_parent' => ''));
$donvisoanthao_list = $donvisoanthao->get_all_list();
$linhvuc_list = $linhvuc->get_all_list();
$congvan_list = $congvan->get_all_list();
$dinhkem = array();
if(isset($_POST['submit'])){
	$id_loaicongvan = isset($_POST['id_loaicongvan']) ? $_POST['id_loaicongvan'] : '';
	$id_donvisoanthao = isset($_POST['id_donvisoanthao']) ? $_POST['id_donvisoanthao'] : '';
	$id_linhvuc = isset($_POST['id_linhvuc']) ? $_POST['id_linhvuc'] : '';
	$trichyeu = isset($_POST['trichyeu']) ? $_POST['trichyeu'] : '';
	$socongvan = isset($_POST['socongvan']) ? $_POST['socongvan'] : '';
	$sothutu = isset($_POST['sothutu']) ? $_POST['sothutu'] : 0;
	$ngayky = isset($_POST['ngayky']) ? convert_date_dd_mm_yyyy($_POST['ngayky']) : '';
	$ngaydiden = isset($_POST['ngaydiden']) ? convert_date_dd_mm_yyyy($_POST['ngaydiden']) : '';
	$thoihanbaocao = isset($_POST['thoihanbaocao']) ? convert_date_dd_mm_yyyy($_POST['thoihanbaocao']) : '';
	$canbobaocao = isset($_POST['canbobaocao']) ? $_POST['canbobaocao'] : '';
	$cacvanbancolienquan = isset($_POST['cacvanbancolienquan']) ? $_POST['cacvanbancolienquan'] : '';
	$ghichu = isset($_POST['ghichu']) ? $_POST['ghichu'] : '';
	$public = isset($_POST['public']) ? intval($_POST['public']) : 0;
	$id_emailaccount = isset($_POST['id_emailaccount']) ? $_POST['id_emailaccount'] : 0;
	$arr_emaillist = array();
	if(isset($id_emailaccount) && $id_emailaccount){
		foreach ($id_emailaccount as $key => $value) {
			array_push($arr_emaillist, array('id_emailaccount' => new MongoId($value), 'view' => 1));
		}
	}
	//dinhkem files
	$alias_name = isset($_POST['alias_name']) ? $_POST['alias_name'] : '';
	$filename = isset($_POST['filename']) ? $_POST['filename'] : '';
	$filetype = isset($_POST['filetype']) ? $_POST['filetype'] : '';
	$congvan->id_loaicongvan = $id_loaicongvan;
	$congvan->id_donvisoanthao = $id_donvisoanthao;
	$congvan->id_linhvuc = $id_linhvuc;
	$congvan->trichyeu = $trichyeu;
	$congvan->socongvan = $socongvan; 
	$congvan->sothutu = intval($sothutu); 
	$congvan->ngayky = $ngayky ? new MongoDate($ngayky) : '';
	$congvan->ngaydiden = $ngaydiden ? new MongoDate($ngaydiden) : '';
	$congvan->thoihanbaocao = $thoihanbaocao ? new MongoDate($thoihanbaocao) : '';
	$congvan->canbobaocao = $canbobaocao;
	$congvan->cacvanbancolienquan = $cacvanbancolienquan;
	$congvan->ghichu = $ghichu;
	$congvan->public = $public;
	$congvan->emaillist = $arr_emaillist;
	$congvan->id_user = $users->get_userid();

	if($alias_name){
		foreach ($alias_name as $key => $value) {
			array_push($dinhkem, array('alias_name' => $value, 'filename' => $filename[$key], 'filetype'=>$filetype[$key]));
		}
	}
	$congvan->dinhkem = $dinhkem;
	if($congvan->insert()){
		if($id_loaicongvan) {
			$loaivanban->id = $id_loaicongvan; $lvb = $loaivanban->get_one();
			if(isset($lvb['id_parent']) && $lvb['id_parent'] && $lvb['id_parent'] == '558a4883020ade300d0008ff'){
				transfers_to('danhsachcongvandi.php');
			} else {
				transfers_to('danhsachcongvanden.php');
			}
		} else {
			transfers_to('danhsachcongvanden.php');
		}
		//transfers_to('danhsachcongvan.php');
	} else {
		$msg = 'Không thể thêm';
	}
}
?>
<script type="text/javascript" src="js/select2.min.js"></script>
<script type="text/javascript" src="js/nhaplieu.js"></script>
<script type="text/javascript" src="js/jquery.inputmask.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".select2").select2();$(".ngaythangnam").inputmask();
		// display file contents
		$(".dinhkem").change(function() {
			$("#progressBar").show(); 
			var interval1; clearInterval(interval1);
			var pb = $("#pb1").data('progress');
			var val = 0;
			setInterval(function(){
				val += 1; pb.set(val);
			});
            
			var formData = new FormData($("#nhaplieuform")[0]);
		   $.ajax({
            url: "post.upload.php",
            type: "POST",
            data: formData,
            async: false,
            success: function(datas) {
                if(datas=='Failed'){
                    $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Thêm học tập"});
                } else {
                	$(".info").remove();
                    $("#files").prepend(datas); delete_file();
                    val = 100; pb.set(val);
                   //$.Notify({type: 'info', caption: 'Thông báo', content: datas});
                   $("#progressBar").fadeOut(3000); 
                }
          	},
          	cache: false,
        	contentType: false,
        	processData: false
	        }).fail(function() {
	            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Upload tập tin"});
	        });
		});
		delete_file(); $("#progressBar").hide();
});
</script>
<h1><a href="index.php" class="nav-button transform"><span></span></a>&nbsp;Thêm Công văn mới</h1>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" id="nhaplieuform" enctype="multipart/form-data" data-role="validator" data-show-required-state="false">
<div class="grid">	
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Loại Công văn</div>
		<div class="cell colspan10 input-control select">
			<select name="id_loaicongvan" id="id_loaicongvan" class="select2">
				<option value="">Loại công văn</option>
				<?php
				if($parent_loaivanban){
					foreach ($parent_loaivanban as $p) {
						echo '<optgroup label="'.$p['ten'].'">';
						$childs = $loaivanban->get_list_condition(array('id_parent' => new MongoId($p['_id'])));
						if($childs){
							foreach ($childs as $child) {
								if($p['_id'] == '558a4871020adebc0e0062b0') $l = 'Đến';
								else $l = 'Đi';
								echo '<option value="'.$child['_id'].'">'.$child['ten'].' ['.$l.']</option>';
							}
						}
						echo '</optgroup>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Đơn vị soạn thảo</div>
		<div class="cell colspan10 input-control select">
			<select name="id_donvisoanthao" id="id_donvisoanthao" class="select2">
				<option value="">Đơn vị soạn thảo</option>
				<?php
				if($donvisoanthao_list){
					foreach ($donvisoanthao_list as $p) {
						echo '<option value="'.$p['_id'].'">'.$p['ten'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Lĩnh vực</div>
		<div class="cell colspan10 input-control select">
			<select name="id_linhvuc" id="id_linhvuc" class="select2">
				<option value="">Lĩnh vực</option>
				<?php
				if($linhvuc_list){
					foreach ($linhvuc_list as $lv) {
						echo '<option value="'. $lv['_id'].'">'.$lv['ten'].'</option>';
						
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Trích yếu</div>
		<div class="cell colspan10 input-control input">
			<input type="text" name="trichyeu" id="trichyeu" placeholder="Trích yếu công văn" />
		</div> 
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Số công văn</div>
		<div class="cell colspan4 input-control input">
			<input type="text" name="socongvan" id="socongvan" placeholder="Số công văn" />
		</div> 
		<div class="cell colspan2 padding-top-10">Số thứ tự</div>
		<div class="cell colspan4 input-control input">
			<input type="text" name="sothutu" id="sothutu" placeholder="Số thứ tự" />
		</div> 
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Ngày ký</div>
		<div class="cell colspan4 align-left input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngayky" id="ngayky" placeholder="Ngày ký" value="" data-inputmask="'alias': 'date'" class="ngaythangnam" />
                <button class="button"><span class="mif-calendar"></span></button>
        </div>
		<div class="cell colspan2 padding-top-10">Ngày đi/ngày đến</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="ngaydiden" id="ngaydiden" placeholder="Ngày đi/ngày đến" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
			<button class="button"><span class="mif-calendar"></span></button>
		</div> 
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Thời hạn báo cáo</div>
		<div class="cell colspan4 input-control text" data-role="datepicker" data-format="dd/mm/yyyy">
			<input type="text" name="thoihanbaocao" id="thoihanbaocao" placeholder="Thời hạn báo cáo" data-inputmask="'alias': 'date'" class="ngaythangnam"/>
			<button class="button"><span class="mif-calendar"></span></button>
		</div> 
		<div class="cell colspan2 padding-top-10">Cán bộ báo cáo</div>
		<div class="cell colspan4 input-control text">
			<input type="text" name="canbobaocao" id="canbobaocao" placeholder="Cán bộ báo cáo" />
		</div> 
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Văn bản có liên quan</div>
		<div class="cell colspan10 input-control select">
			<select name="cacvanbancolienquan[]" class="select2" multiple="multiple">
				<option value="">Các văn bản có liên quan</option>
				<?php
				if($congvan_list){
					foreach ($congvan_list as $cv) {
						echo '<option value="'. $cv['_id'].'">'.$cv['socongvan'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Ghi chú</div>
		<div class="cell colspan10 input-control textarea">
			<textarea name="ghichu" id="ghichu" placeholder="Ghi chú về văn bản"></textarea>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Public</div>
		<div class="cell colspan10">
			<label class="switch">
    			<input type="checkbox" name="public" id="public" value="1" />
    			<span class="check"></span>
			</label>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Email</div>
		<div class="cell colspan10 input-control select" data-role="select" data-placeholder="Chọn địa chỉ Email">
			<select name="id_emailaccount[]" class="select2" multiple>
				<?php
				if($email_list){
					foreach ($email_list as $e) {
						echo '<option value="'.$e['_id'].'">'.$e['emailaddress'].'</option>';
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row cells12">
		<div class="cell colspan2 padding-top-10">Đính kèm</div>
		<div class="cell colspan10 input-control file" data-role="input">
			<input type="file" name="dinhkem[]" multiple="multiple" class="dinhkem" placeholder="Chọn tập tin cho công văn" />
    		<button class="button"><span class="mif-folder"></span></button>
    		<?php
    		echo '<small><b>Tập tin được phép upload:</b> ';
    		foreach ($valid_formats as $key => $value) {
    			echo $value . '   ';
    		}
    		echo '</small>';
    		?>
		</div>
	</div>
	<div class="row cells12" id="progressBar">
		<div class="cell colspan2"></div>
		<div class="cell colspan10">
			<div class="progress small" id="pb1" data-role="progress" data-value="0" data-color="bg-blue"></div>
		</div>
	</div>
	<div id="files"></div>
	<div class="row cells12">
        <div class="cell colspan12 padding10 align-center">
            <button class="button primary" name="submit" value="OK" id="submit"><span class="mif-checkmark"></span> Cập nhật</button>
            <a href="index.php" class="button" name="cancel" ><span class="mif-blocked"></span> Huỷ</a>
        </div>
    </div>
</div>
</form>
<?php require_once('footer.php'); ?>
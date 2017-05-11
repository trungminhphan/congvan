<?php
require_once('inc/config.inc.php');
$valid_formats = array("jpg", "png", "gif", "zip", "bmp", "doc", "docx", "pdf", "xls", "xlsx", "ppt", "pptx");
$max_file_size = 50*1024*1024; //50MB
//$path = "uploads/"; // Upload directory
$count = 0;

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	if(isset($_FILES['dinhkem']['name']) && $_FILES['dinhkem']['name']){
		// Loop $_FILES to exeicute all files
		foreach ($_FILES['dinhkem']['name'] as $f => $name) {   
		    if ($_FILES['dinhkem']['error'][$f] == 4) {
		        echo 'Failed';
		        continue; // Skip file if any error found
		    } 

		    if ($_FILES['dinhkem']['error'][$f] == 0) {	           
		        if ($_FILES['dinhkem']['size'][$f] > $max_file_size) {
		        	echo '<div class="row cells12">';
					echo '<div class="cell colspan2"></div>';
					echo '<div class="cell colspan10">';
		            echo '<div class="info bg-red padding10 fg-white"><span class="mif-blocked"></span> '. $name.' quá lớn!.';
		            echo '</div>';
					echo '</div>';
		            continue; // Skip large files
		        } elseif(!in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), $valid_formats) ){
					echo '<div class="row info cells12">';
					echo '<div class="cell colspan2"></div>';
					echo '<div class="cell colspan10">';
					echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> ' . $name .' không được phép';
					echo '</div>';
					echo '</div>';
					continue; // Skip invalid file formats
				}
		        else{ // No error found! Move uploaded files 
					$extension = pathinfo($name, PATHINFO_EXTENSION);
					$alias = md5($name);
					$alias_name = $alias . '_'. date("Ymdhms") . '.' . $extension;
		            if(move_uploaded_file($_FILES["dinhkem"]["tmp_name"][$f], $target_files.$alias_name))
		            $count++; // Number of successfully uploaded file
		        	echo '<div class="row cells12">';
						echo '<div class="cell colspan2"></div>';
						echo '<div class="cell colspan10 input-control text">';
							echo '<input type="hidden" name="alias_name[]" value="'.$alias_name.'" readonly/>';
							echo '<input type="hidden" name="filetype[]" value="'.$extension.'" readonly/>';
							echo '<input type="text" name="filename[]" value="'.$name.'" class="bg-grayLighter"/>';
							echo '<a href="get.xoataptin.php?filename='.$alias_name.'" onclick="return false;" class="delete_file button"><span class="mif-cross fg-red"></span></a>';
						echo '</div>';
					echo '</div>';
		        }
		    }
		}
	} else {
		echo '<div class="info row cells12">';
		echo '<div class="cell colspan2"></div>';
		echo '<div class="cell colspan10 input-control text">';
		echo '<div class="bg-red padding10 fg-white"><span class="mif-blocked"></span> Không đủ bộ nhớ để upload, vui lòng chọn lại ít tập tin hơn</div>';
		echo '</div>';
		echo '</div>';
	}
}
?>
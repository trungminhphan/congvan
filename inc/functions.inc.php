<?php
function convert_string_number($string){
	$len_of_string = strlen($string);
	$i = 0;
	$number = '';
	for($i=0; $i<$len_of_string; $i++){
		if($string[$i] != ".") $number .= $string[$i];
	}
	$number = str_replace(",",".",$number);
	doubleval($number);
	return $number;
}

function transfers_to($url){ 	header('Location: ' . $url); }

function format_number($number){
	return number_format($number, 0, ",", ".");
}

function format_decimal($number, $dec){
	return number_format($number, $dec, ",", ".");
}
function format_date($date){
	return date("d/m/Y",strtotime($date));
}

function show_gioitinh($gioitinh){
	if($gioitinh == 1) return 'Nam';
	else return 'Nữ';
}

function quote_smart($value){
    if(get_magic_quotes_gpc()){
		$value=stripcslashes($value);    
    }
	$value=addslashes($value);
	return $value;    
}

function convert_date_mm_yyyy($string_date){
	$s = explode ("/", $string_date);
	return strtotime($s[1] . '-'. $s[0] . '-01 00:00:00');
}

function convert_date_dd_mm_yyyy($string_date){
	if($string_date){
		$s = explode ("/", $string_date);
		return strtotime($s[2].'-'.$s[1].'-'.$s[0] . ' 00:00:00');	
	} else {
		return '';
	}
	
}

function checkDateTime($data) {
    if (date('Y-m-d H:i:s', strtotime($data)) == $data) {
        return true;
    } else {
        return false;
    }
}
function show_icon($icon){
	$str_icon = '';
	switch(strtolower($icon)){
		case 'pdf': $str_icon = 'mif-file-pdf'; break;
		case 'doc': $str_icon = 'mif-file-word'; break;
		case 'docx': $str_icon = 'mif-file-word'; break;
		case 'ppt': $str_icon = 'mif-file-powerpoint'; break;
		case 'pptx': $str_icon = 'mif-file-powerpoint'; break;
		case 'xls': $str_icon = 'mif-file-excel'; break;
		case 'xlsx': $str_icon = 'mif-file-excel'; break;
		case 'zip': $str_icon = 'mif-file-zip'; break;
		case 'rar': $str_icon = 'mif-file-zip'; break;
		case '7z': $str_icon = 'mif-file-zip'; break;
		case 'jpg': $str_icon = 'mif-images'; break;
		case 'png': $str_icon = 'mif-images'; break;
		case 'jpeg': $str_icon = 'mif-images'; break;
		case 'gif': $str_icon = 'mif-images'; break;
		default: 
			$str_icon = 'mif-attachment';
	}

	return '<span class="'.$str_icon.'"></span>';
}
?>
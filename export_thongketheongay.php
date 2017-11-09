<?php
require_once('header_none.php'); 
require_once('cls/PHPExcel.php');
$congvan = new CongVan();$loaicongvan = new LoaiVanBan();
if(isset($_GET['submit'])){
	$id_loaicongvan = isset($_GET['id_loaicongvan']) ? $_GET['id_loaicongvan'] : '';
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';
	$arr_query = array();$arr_congvan = array();
	if($id_loaicongvan){
		$list_congvan = $loaicongvan->get_list_condition(array('id_parent' => new MongoId($id_loaicongvan)));
		foreach ($list_congvan as $key => $value) {
			$arr_congvan[] = new MongoId($value['_id']);
		}
		array_push($arr_query, array('id_loaicongvan' => array('$in' => $arr_congvan)));
	}
	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn sai ngày....';
	} else {
		$date1 = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$date2 = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		//$query = array(array('ngaydiden' => array('$gte' => $date1), 'ngaydiden' => array('$lte' => $date2)));
		//$query = array('ngaydiden' => array('$gte' => $date1));
		//$query = array('ngaydiden' => array('$lte' => $date2));
		//$query = array('$and' => array(array('ngaydiden' => array('$gte' => $date1)), array('ngaydiden' => array('$lte' => $date2))));
		array_push($arr_query, array('ngaydiden' => array('$gte' => $date1)));
		array_push($arr_query, array('ngaydiden' => array('$lte' => $date2)));
		$query = array('$and' => $arr_query);
		$list = $congvan->get_list_condition($query);
	}
}

if(isset($list) && $list){
	$inputFileName = 'templates/thongketheongay.xlsx';
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
								 ->setLastModifiedBy("Phan Minh Trung")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("xuat du lieu cong van");
	$objPHPExcel->setActiveSheetIndex(0);
	$string_title = 'Từ ngày: ' . $tungay . '     Đến ngày: ' . $denngay;
	$objPHPExcel->setActiveSheetIndex()->setCellValue('A2', $string_title);
	$i = 4; $stt=1;
	foreach ($list as $l) {
		$ngayky = (isset($l['ngayky']) && $l['ngayky']) ? date("d/m/Y", $l['ngayky']->sec) : '';
		$ngaydiden = isset($l['ngaydiden']) ? date("d/m/Y", $l['ngaydiden']->sec) : '';
		if(isset($l['id_loaicongvan']) && $l['id_loaicongvan']){
			$loaicongvan->id = $l['id_loaicongvan']; $lcv = $loaicongvan->get_one();
			$loaicongvan->id_parent = $lcv['id_parent']; $p = $loaicongvan->get_parent_name();
		} else {
			 $p = '';
		}
		if(isset($l['id_donvisoanthao']) && $l['id_donvisoanthao']){
			$donvisoanthao = new DonViSoanThao();
			$donvisoanthao->id = $l['id_donvisoanthao']; $dvst = $donvisoanthao->get_one();
			$donvisoanthao = $dvst['ten'];
		} else { $donvisoanthao = ''; }
		
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, isset($l['sothutu']) ? $l['sothutu'] : '');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $l['trichyeu']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $l['socongvan']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $donvisoanthao);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, isset($l['nguoiky']) ? $l['nguoiky'] : '');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $ngayky);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $ngaydiden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $l['ghichu']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $p ? $p : '');
		$i++;$stt++;
	}
}

// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongketheongay_'.date("Ymdhis").'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
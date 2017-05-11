<?php
require_once('header_none.php'); 
require_once('cls/PHPExcel.php');
$congvan = new CongVan();
$loaivanban = new LoaiVanBan();
$id_loaivanban = isset($_GET['id_loaivanban']) ? $_GET['id_loaivanban'] : '';

$arr_loaivanban = array();
$lvb_list = $loaivanban->get_list_condition(array('id_parent' => new MongoId($id_loaivanban)));
if($lvb_list && $lvb_list->count() > 0){
	foreach ($lvb_list as $vb) {
		array_push($arr_loaivanban, new MongoId($vb['_id']));
	}
} else {
	array_push($arr_loaivanban, new MongoId($id_loaivanban));
}
$congvan_list = $congvan->get_list_condition(array('id_loaicongvan' => array('$in' => $arr_loaivanban)));

if(isset($congvan_list) && $congvan_list){
	$inputFileName = 'templates/loaicongvan.xlsx';
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	$objPHPExcel->getProperties()->setCreator("Phan Minh Trung")
								 ->setLastModifiedBy("Phan Minh Trung")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("xuat du lieu cong van");
	$objPHPExcel->setActiveSheetIndex(0);
	$i=3;//$stt=$congvan_list->count();
	foreach ($congvan_list as $cv) {
		$stt = isset($cv['sothutu']) ? $cv['sothutu']: '0';
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $cv['socongvan']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $cv['trichyeu']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $cv['ngayky'] ? date("d/m/Y",$cv['ngayky']->sec) : '');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $cv['ngaydiden'] ? date("d/m/Y",$cv['ngaydiden']->sec) : '');
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $cv['ghichu']);
		$i++; //$stt--;
	}
	// Redirect output to a client’s web browser (Excel2007)
	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="export_loaicongvan.xlsx"');
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
}

?>
<?php
require_once('header_none.php'); 
require_once('cls/PHPExcel.php');

$congvan = new CongVan();$loaicongvan = new LoaiVanBan();
$arr_id_baocao = array(new MongoId('558a48c8020adebc0c001243'), new MongoId('561f5dc8f777dc3c08007514'));
if(isset($_GET['submit'])){
	$tungay = isset($_GET['tungay']) ? $_GET['tungay'] : '';
	$denngay = isset($_GET['denngay']) ? $_GET['denngay'] : '';

	if(convert_date_dd_mm_yyyy($tungay) > convert_date_dd_mm_yyyy($denngay)){
		$msg = 'Chọn sai ngày....';
	} else {
		$date1 = new MongoDate(convert_date_dd_mm_yyyy($tungay));
		$date2 = new MongoDate(convert_date_dd_mm_yyyy($denngay));
		//$query = array(array('ngaydiden' => array('$gte' => $date1), 'ngaydiden' => array('$lte' => $date2)));
		//$query = array('ngaydiden' => array('$gte' => $date1));
		//$query = array('ngaydiden' => array('$lte' => $date2));

		$query = array('$and' => array(array('ngaydiden' => array('$gte' => $date1)), array('ngaydiden' => array('$lte' => $date2)), array('id_loaicongvan' => array('$in' => $arr_id_baocao))));
		$list = $congvan->get_list_baocao($query);
	}
}

if(isset($list) && $list){
	$inputFileName = 'templates/thongkebaocao.xlsx';
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
	$date1=date_create(date("Y-m-d"));
	foreach ($list as $l) {
		$ngaydiden = isset($l['ngaydiden']) ? date("d/m/Y", $l['ngaydiden']->sec) : '';
		$thoihanbaocao = isset($l['thoihanbaocao']->sec) ? date("d/m/Y", $l['thoihanbaocao']->sec) : '';
		if(isset($l['dabaocao']) && $l['dabaocao']){
			$dabaocao = 'X';
		} else {
			$dabaocao = '';
		}
		$loaicongvan->id = $l['id_loaicongvan']; $lcv = $loaicongvan->get_one();
		$loaicongvan->id_parent = $lcv['id_parent']; $p = $loaicongvan->get_parent_name();
		if(isset($l['dabaocao']) && $l['dabaocao']==0 && isset($l['thoihanbaocao']->sec)){
			$date2=date_create(date("Y-m-d", $l['thoihanbaocao']->sec));
			$diff=date_diff($date1,$date2); $songay = $diff->format("%R%a");
		} else {
			$songay = '';
		}
		$objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$i, $stt);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$i, $l['socongvan']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$i, $l['trichyeu']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$i, $ngaydiden);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$i, $thoihanbaocao);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$i, $songay);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$i, $l['canbobaocao']);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$i, $dabaocao);
		$objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$i, $p ? $p : '');
		$i++;$stt++;

	}
}
// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="thongkebaocao_'.date("Ymdhis").'.xlsx"');
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


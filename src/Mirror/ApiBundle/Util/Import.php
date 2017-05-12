<?php 
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../Util/');
	require(PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php');
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');//use excel2007 for 2007 format 
    $objPHPExcel = $objReader->load($file);
    $sheet = $objPHPExcel->getSheet(0); 
    $highestRow = $sheet->getHighestRow();           //取得总行数 
    $highestColumn = $sheet->getHighestColumn(); //取得总列数
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow(); 
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
    $headtitle=array(); 
?>
<?php
namespace Mirror\ApiBundle\Util;

class ExcelReader{
	public function readFile($filePath)
	{
		$dirName = dirname(__FILE__);
		include $dirName.'/PHPExcel.php';
		include $dirName.'/PHPExcel/Writer/Excel2007.php';
		include $dirName.'/PHPExcel/Reader/Excel2007.php';
		include $dirName.'/PHPExcel/Reader/Excel5.php';
		include $dirName.'/PHPExcel/IOFactory.php';
		//PHPExcel\Reader
		$reader = new \PHPExcel_Reader_Excel2007();
		$excel = new \PhpExcel();
		if(!$reader->canRead($filePath)){
			$reader = new \PHPExcel_Reader_Excel5();
			if(!$reader->canRead($filePath)){
				echo 'no Excel';
				return ;
			}
		}
		$excel = $reader->load($filePath);
		return $excel;
	}
}
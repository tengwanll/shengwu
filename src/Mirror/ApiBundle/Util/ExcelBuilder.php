<?php

namespace Mirror\ApiBundle\Util;

class ExcelBuilder {
	public $charMap = array (
			'A',
			'B',
			'C',
			'D',
			'E',
			'F',
			'G',
			'H',
			'I',
			'J',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'Q',
			'R',
			'S',
			'T',
			'U',
			'V',
			'W',
			'X',
			'Y',
			'Z' 
	);
	public function create($title, $value, $flag = true) {
		if (! class_exists ( 'PHPExcel' )) {
			$dirName = dirname ( __FILE__ );
			include $dirName . '/PHPExcel.php';
			include $dirName . '/PHPExcel/Writer/Excel2007.php';
		}
		$objPHPExcel = new \PHPExcel ();
		// Add some data
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$workSheet = $objPHPExcel->getActiveSheet ();
		$index = 1;
		for($i = 0; $i < count ( $title ); $i ++) {
			$temp = $title [$i];
			if ($flag) {
				$temp = mb_convert_encoding ( $title [$i], "UTF-8", "gbk" );
			}
			$this->fillCell ( $i, $index, $temp, $workSheet );
		}
		$index ++;
		unset ( $title );
		
		/*
		 * foreach ( $value as $tempValue ) { $i = 0; foreach ( $tempValue as $str ) { $this->fillCell ( $i, $index, $str, $workSheet ); $i ++; } $index ++; unset($tempValue); echo 'value '.$index.':'.memory_get_usage()."<br/>"; }
		 */
		
		while ( count ( $value ) != 0 ) {
			$i = 0;
			$tempValue = array_shift ( $value );
			foreach ( $tempValue as $str ) {
				$this->fillCell ( $i, $index, $str, $workSheet );
				$i ++;
			}
			$index ++;
			unset ( $tempValue );
		}
		unset ( $value );
		// Rename sheet
		$workSheet->setTitle ( 'Simple' );
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
		// Save Excel 2007 file
		$objWriter = new \PHPExcel_Writer_Excel2007 ( $objPHPExcel );
		// $objWriter->save('php://output');
		$now = new \Datetime ();
		$fileName = $now->format ( "YmdHis" );
		$fullName = 'excel/' . $fileName . '.xlsx';
		$objWriter->save ( $fullName );
		return $fileName . '.xlsx';
	}
	public function pageExport($title, $value, $flag = true, $filename, $index) {
		if (! class_exists ( 'PHPExcel' )) {
			$dirName = dirname ( __FILE__ );
			include $dirName . '/PHPExcel.php';
			include $dirName . '/PHPExcel/Writer/Excel2007.php';
			include $dirName . '/PHPExcel/Reader/Excel2007.php';
			include $dirName . '/PHPExcel/IOFactory.php';
			include $dirName.'/PHPExcel/Reader/Excel5.php';
		}
		$objPHPExcel = new \PHPExcel ();
		echo $filename;
		if ($index) {
			$reader = new \PHPExcel_Reader_Excel2007();
			$objPHPExcel = $reader->load($filename);
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$workSheet = $objPHPExcel->getActiveSheet ();
			
			while ( count ( $value ) != 0 ) {
				$i = 0;
				$tempValue = array_shift ( $value );
				foreach ( $tempValue as $str ) {
					$this->fillCell ( $i, $index, $str, $workSheet );
					$i ++;
				}
				$index ++;
				unset ( $tempValue );
			}
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$objWriter = new \PHPExcel_Writer_Excel2007 ( $objPHPExcel );
			$objWriter->save ( $filename );
			return array (
					'fileName' => $filename,
					'index' => $index
			);
		} else {
			// Add some data
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$workSheet = $objPHPExcel->getActiveSheet ();
			$index = 1;
			for($i = 0; $i < count ( $title ); $i ++) {
				$temp = $title [$i];
				if ($flag) {
					$temp = mb_convert_encoding ( $title [$i], "UTF-8", "gbk" );
				}
				$this->fillCell ( $i, $index, $temp, $workSheet );
			}
			$index ++;
			unset ( $title );
			while ( count ( $value ) != 0 ) {
				$i = 0;
				$tempValue = array_shift ( $value );
				foreach ( $tempValue as $str ) {
					$this->fillCell ( $i, $index, $str, $workSheet );
					$i ++;
				}
				$index ++;
				unset ( $tempValue );
			}
			unset ( $value );
			$workSheet->setTitle ( 'Simple' );
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$objWriter = new \PHPExcel_Writer_Excel2007 ( $objPHPExcel );
			$now = new \Datetime ();
			$fileName = $now->format ( "YmdHis" );
			$fullName = 'excel/' . $fileName . '.xlsx';
			$objWriter->save ( $fullName );
			return array (
					'fileName' => $fullName,
					'index' => $index 
			);
		} 
	}
	public function createExcel() {
		if (! class_exists ( 'PHPExcel' )) {
			$dirName = dirname ( __FILE__ );
			include $dirName . '/PHPExcel.php';
			include $dirName . '/PHPExcel/Writer/Excel2007.php';
		}
// 		$method = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
// 		\PHPExcel_Settings::setCacheStorageMethod($method);
		$objPHPExcel = new \PHPExcel ();
		return $objPHPExcel;
	}
	public function saveFile($fileName, $objExcel) {
		if (! class_exists ( 'PHPExcel' )) {
			$dirName = dirname ( __FILE__ );
			include $dirName . '/PHPExcel.php';
			include $dirName . '/PHPExcel/Writer/Excel2007.php';
		}
		$objWriter = new \PHPExcel_Writer_Excel2007 ( $objExcel );
		$fileName = 'excel/' . $fileName;
		$objWriter->save ( $fileName );
		return $fileName;
	}
	public function fillCell($x, $y, $value, $workSheet) {
		$titleName = $this->getTitleNumber ( $x );
		$cell = $workSheet->setCellValue ( $titleName . $y, $value, true );
		// $cell->setDataType('str');
	}
	private function getTitleNumber($number) {
		$result = '';
		do {
			if ($number / 26 >= 1) {
				$c = $this->charMap [$number / 26 - 1];
				$number = $number % 26;
				$result = $result . $c;
			}
		} while ( $number > 26 );
		$c = $this->charMap [$number % 26];
		$result = $result . $c;
		return $result;
	}
}
<?php
class ExcelDown {
	/**
	 * 
	 * @param string $filename 文件名称 
	 * @param array $data 数据 eg:array(0=>array('user_name' => '用户001'))
	 * @param array $fieldsDesc 数据列属性  
	 * 	eg:array('user_name' =>array('desc'=>'列名','style'=>'str','width'=>20))
	 * 	style:str-字符串，num-数字；width:列宽 默认20
	 * @param string $title 表头
	 */
	public static function run($filename, $data = array(), $fieldsDesc = array(), $title = "") {
		set_time_limit(0);  //解决导出超时问题
		spl_autoload_unregister ( array ('YiiBase', 'autoload' ) ); //关闭yii的自动加载功能
		Yii::import ( 'application.extensions.PHPExcel.PHPExcel', true );
		$filename = iconv ( 'utf-8', 'gbk', $filename . date ( 'Y-m-d' ) . '.xls' );
		
		$totalSheet = ceil ( count ( $data ) / 65530);
		$sheetData = array();
		for($s = 0;$s<$totalSheet;$s++){
			$sheetData[$s] = array_slice($data,$s*65530,65530);
		}
		/** PHPExcel */
		//设置单元格缓存方式,默认为内存缓存
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;   
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod);  
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel ();
		
		// Set properties
		$objPHPExcel->getProperties ()->setCreator ( "TrunkBow" )
		                              ->setLastModifiedBy ( "TrunkBow" )
		                              ->setTitle ( "Office 2003 XLS Document" )
		                              ->setSubject ( "Office 2003 XLS Document" )
		                              ->setDescription ( "TrunkBow" )
		                              ->setKeywords ( "TrunkBow" )
		                              ->setCategory ( "TrunkBow" );
		
		//set cell style
		foreach($sheetData as $sheet => $rows) {
			$t = ord ( 'A' );
			$i = 1;
			if($sheet!=0)
				$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($sheet);
			$activeSheet = $objPHPExcel->getActiveSheet();
			//表头
			if($title&&$sheet==0){
				$range = ord ( 'A' )+count($fieldsDesc)-1;
				$mergeRange = "A1:".chr($range)."1";
				$activeSheet -> mergeCells($mergeRange);
				$a1Style = $activeSheet -> getStyle ("A1");
				$a1Style -> getFont () -> setBold ( true );
				$a1Style -> getAlignment ()
					-> setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$a1Style -> getAlignment() 
					-> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$activeSheet -> getRowDimension(1)->setRowHeight(30);
				$activeSheet -> setCellValue("A1",$title);
				$i++;
			}
			//列名
			foreach ( $fieldsDesc as $desc ) {
				$activeSheet ->setCellValue ( chr ( $t ) . $i, $desc['desc'] );
				$activeSheet ->getStyle ( chr ( $t ) . $i )
					->getFont ()
					->setBold ( true );
				$col_width = $desc['width']?$desc['width']:20;
				$activeSheet ->getColumnDimension ( chr ( $t ) )->setWidth($col_width);
				$t ++;
			}
			//整体格式
			$range = 'A1'.':'.chr($t-1).($i+count($rows));
			$activeSheet ->getStyle ($range)
				->getNumberFormat()
				->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
			
			$activeSheet ->getStyle ($range)
				->getAlignment ()
				->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			
			$styleBorders = array(
					'borders' => array(
							'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
							),
					),
			);
			$activeSheet->getStyle($range)->applyFromArray($styleBorders);
		
		}
		
		//数据
		if ($sheetData) {
			foreach ( $sheetData as $sheet =>$rows ) {
				if($title=='')
					$i = 2;
				else 
					$i = 3;
				
				$j = ord ( 'A' );
				$objPHPExcel->setActiveSheetIndex($sheet)->setTitle('sheet'.($sheet+1));
				foreach($rows as $row){
					foreach($fieldsDesc as $k =>$v ){
						$cell_v = $row[$k];
						if($row[$k]==''){
							if($v['style']=='num')
								$cell_v = 0;
						}else{
							if($v['style']=='arr')
								$cell_v = $v['value'][$row[$k]];
						}
							
						$objPHPExcel->getActiveSheet() ->
							setCellValue ( chr ( $j ++ ) . $i," ".$cell_v);
					}
					$j = ord ( 'A' );
					$i ++;
				}
				
			}
		}
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( "Content-Disposition: attachment;filename=$filename" ); 
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
		Yii::app()->end();
		spl_autoload_register ( array ('YiiBase', 'autoload' ) ); //打开yii的自动加载功能
		//exit();
	
	}
	
}
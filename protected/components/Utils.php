<?php

class Utils {
	
	public static function hx2bin($str) {
		
		$len = strlen ( $str );
		$nstr = "";
		for($i = 0; $i < $len; $i += 2) {
			$num = sscanf ( substr ( $str, $i, 2 ), "%x" );
			$nstr .= chr ( $num [0] );
		}
		return $nstr;
	}
	
	private static function to64($v, $n) {
		$ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		
		$ret = "";
		while ( ($n - 1) >= 0 ) {
			$n --;
			$ret .= $ITOA64 [$v & 0x3f];
			$v = $v >> 6;
		}
		
		return $ret;
	
	}
	
	public static function MonthLast($last = -1, $fm = 'Y-m-d') {
	
	}
	
	public static function md5crypt($pw, $salt, $magic = "") {
		
		$MAGIC = "$1$";
		
		if ($magic == "")
			$magic = $MAGIC;
		
		$slist = explode ( "$", $salt );
		if ($slist [0] == "1")
			$salt = $slist [1];
		$salt = substr ( $salt, 0, 8 );
		
		$ctx = $pw . $magic . $salt;
		
		$final = self::hx2bin ( md5 ( $pw . $salt . $pw ) );
		
		for($i = strlen ( $pw ); $i > 0; $i -= 16) {
			if ($i > 16)
				$ctx .= substr ( $final, 0, 16 );
			else
				$ctx .= substr ( $final, 0, $i );
		}
		
		$i = strlen ( $pw );
		while ( $i > 0 ) {
			if ($i & 1)
				$ctx .= chr ( 0 );
			else
				$ctx .= $pw [0];
			$i = $i >> 1;
		}
		
		$final = self::hx2bin ( md5 ( $ctx ) );
		
		# this is really stupid and takes too long
		

		for($i = 0; $i < 1000; $i ++) {
			$ctx1 = "";
			if ($i & 1)
				$ctx1 .= $pw;
			else
				$ctx1 .= substr ( $final, 0, 16 );
			if ($i % 3)
				$ctx1 .= $salt;
			if ($i % 7)
				$ctx1 .= $pw;
			if ($i & 1)
				$ctx1 .= substr ( $final, 0, 16 );
			else
				$ctx1 .= $pw;
			$final = self::hx2bin ( md5 ( $ctx1 ) );
		}
		
		$passwd = "";
		
		$passwd .= self::to64 ( ((ord ( $final [0] ) << 16) | (ord ( $final [6] ) << 8) | (ord ( $final [12] ))), 4 );
		$passwd .= self::to64 ( ((ord ( $final [1] ) << 16) | (ord ( $final [7] ) << 8) | (ord ( $final [13] ))), 4 );
		$passwd .= self::to64 ( ((ord ( $final [2] ) << 16) | (ord ( $final [8] ) << 8) | (ord ( $final [14] ))), 4 );
		$passwd .= self::to64 ( ((ord ( $final [3] ) << 16) | (ord ( $final [9] ) << 8) | (ord ( $final [15] ))), 4 );
		$passwd .= self::to64 ( ((ord ( $final [4] ) << 16) | (ord ( $final [10] ) << 8) | (ord ( $final [5] ))), 4 );
		$passwd .= self::to64 ( ord ( $final [11] ), 2 );
		
		return "$magic$salt\$$passwd";
	
	}
	
	/**
	 * 产生action的权限配置文件
	 * @param <string> 模块名称, 为空表示app下Controller
	 */
	public function getControllersActions($module = '') {
		
		if ($module == '') {
			//Yii::import('application.controllers.*');
			$controllerPath = Yii::app ()->basePath . '/controllers';
			$path = get_include_path ();
			set_include_path ( $controllerPath . PATH_SEPARATOR . $path );
		} else {
			//Yii::import('application.modules.'.$module.'.controllers.*');
			$controllerPath = Yii::app ()->basePath . '/modules/' . $module . '/controllers';
			$path = get_include_path ();
			set_include_path ( $controllerPath . PATH_SEPARATOR . $path );
		}
		$a = array ();
		
		$d = @dir ( $controllerPath );
		if (false === $d)
			return array ();
		while ( false !== ($entry = @$d->read ()) )
			if ($entry != '..' && $entry != '.' && substr ( $entry, - 14 ) == 'Controller.php') {
				//echo $entry,'<br/>';
				$controller = substr ( $entry, 0, strlen ( $entry ) - 4 );
				//echo $controller,'<br/>';
				$class = new ReflectionClass ( $controller );
				$methods = $class->getMethods ();
				foreach ( $methods as $method ) {
					//var_dump($method);
					if ($method->class == $controller && substr ( $method->name, 0, 6 ) == 'action') {
						//echo $method->name,'<br>';
						$a [] = strtolower ( substr ( $controller, 0, strlen ( $controller ) - 10 ) . '/' . substr ( $method->name, 6 ) );
					}
				}
			}
		$d->close ();
		return $a;
	}
	
	public function mb_explode($separator, $string) {
		mb_regex_encoding ( 'UTF-8' );
		return mb_split ( '[' . $separator . ']', $string );
	}
	
	public function is_startwith($string, $start) {
		return substr ( $string, 0, strlen ( $start ) ) == $start;
	}
	
	public static function getMicrotime() {
		list ( $usec, $sec ) = explode ( ' ', microtime () );
		return (( float ) $usec + ( float ) $sec);
	}
	
	public static function json_decode_nice($json, $assoc = FALSE) {
		$json = '"' . $json . '"';
		$json = str_replace ( array ("\n", "\r" ), "", $json );
		$json = preg_replace ( '/([{,])(\s*)([^"]+?)\s*:/', '$1"$3":', $json );
		return json_decode ( $json, $assoc );
	}
	
	public static function chinese_week($time = 0) {
		$w = array ('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六' );
		if ($time == 0)
			$time = time ();
		return $w [date ( 'w', $time )];
	}
	
	public static function getProvince() {
		$rs = array ('' => '', '安徽' => '安徽', '重庆' => '重庆', '福建' => '福建', '甘肃' => '甘肃', '广东' => '广东', '广西' => '广西', '贵州' => '贵州', '海南' => '海南', '河北' => '河北', '河南' => '河南', '黑龙江' => '黑龙江', '湖北' => '湖北', '湖南' => '湖南', '江苏' => '江苏', '江西' => '江西', '吉林' => '吉林', '辽宁' => '辽宁', '内蒙古' => '内蒙古', '宁夏' => '宁夏', '青海' => '青海', '山东' => '山东', '山西' => '山西', '陕西' => '陕西', '四川' => '四川', '天津' => '天津', '西藏' => '西藏', '新疆' => '新疆', '云南' => '云南', '浙江' => '浙江', '香港' => '香港', '澳门' => '澳门', '台湾' => '台湾' );
		return $rs;
	}
	
	public static function getTelegroup() {
		$rs = array ('' => '', '移动' => '移动', '联通' => '联通', '电信' => '电信' )

		;
		return $rs;
	}
	
	public static function getCity($citycode) {
		$rs = array ('130100' => '石家庄', '130200' => '唐山', '130300' => '秦皇岛', '130400' => '邯郸', '130500' => '邢台', '130600' => '保定', '130700' => '张家口', '130800' => '承德', '130900' => '沧州', '131000' => '廊坊', '131100' => '衡水' );
		return $citycode == '_ARRAY' ? $rs : $rs [$citycode];
	}
	
	public static function getArea($city = '') {
		$a = array ('430400' => array ('衡阳市', array ('430401' => '市辖区', '430405' => '珠晖区', '430406' => '雁峰区', '430407' => '石鼓区', '430408' => '蒸湘区', '430412' => '南岳区', '430421' => '衡阳县', '430422' => '衡南县', '430423' => '衡山县', '430424' => '衡东县', '430426' => '祁东县', '430481' => '耒阳市', '430482' => '常宁市' ) ), '430500' => array ('邵阳市', array ('430501' => '市辖区', '430502' => '双清区', '430503' => '大祥区', '430511' => '北塔区', '430521' => '邵东县', '430522' => '新邵县', '430523' => '邵阳县', '430524' => '隆回县', '430525' => '洞口县', '430527' => '绥宁县', '430528' => '新宁县', '430529' => '城步苗族自治县', '430581' => '武冈市' ) ) );
		if ($city != '')
			return $a [$city];
		return $a;
	}
	
	const ORGTYPE_COLLEGE = 1;
	public static function getOrgTpl($orglevel, $orgtype = Utils::ORGTYPE_COLLEGE) {
		if ($orglevel == '')
			return '';
		$rs = array ('0' => array ('0' => '校企', '1' => '校企' ), '1' => array ('0' => '校园', '1' => '学校' ), '2' => array ('0' => '企业', '1' => '企业' ) );
		//echo $orgtype.'--'.$orglevel;
		//echo $rs[$orgtype][$orglevel];
		return $rs [$orgtype] [$orglevel];
	}
	
	public static function getMessageType($type = false) {
		$ar = array ('1' => 'success', '-1' => 'error', '2' => 'notice' );
		return $type ? $ar [$type] : $ar;
	}
	public static function markIdNumber($id) {
		$id = trim ( $id );
		if (strlen ( $id ) != 18)
			return $id;
		return substr ( $id, 0, 3 ) . '********' . substr ( $id, 14, 17 );
	}
	public static function markPhone($phone) {
		$phone = trim ( $phone );
		if (strlen ( $phone ) != 11)
			return $phone;
		return substr ( $phone, 0, 3 ) . '****' . substr ( $phone, 7, 4 );
	}
	
	public static function timeTranslate($time, $timeto = "now") {
		$translate = "";
		$strtotime = strtotime ( $timeto );
		$strtime = strtotime ( $time );
		$differ = $strtotime - $strtime;
		$rs = intval ( $differ / 60 );
		if ($rs < 1) {
			$translate = $differ . "秒前";
		} elseif ($rs < 60) {
			$translate = intval ( $rs ) . "分钟前";
		} else {
			//            $h = intval($differ / 3600);
			//            $m = intval(($differ - $h*3600) / 60);
			//            $translate = $h . "小时" . $m . "分种前";
			$translate = $time;
		}
		return $translate;
	}
	
	/**
	 * @static
	 * @param string $schoolid
	 * @param string $type  'student','staff', '' for all
	 * @return mixed
	 */
	public static function updateSchoolNumber($schoolid = '', $type = 'student') {
		if ($schoolid == '') {
			$schoolid = Yii::app ()->session ['schoolid'];
		}
		if ($schoolid == '')
			return;
		
		if ($type == 'student' || $type == '') {
			$rows = Yii::app ()->db->createCommand ()->select ( 'classid,count(*) as cnt' )->from ( 'student' )->where ( 'schoolid=:schoolid', array (':schoolid' => $schoolid ) )->group ( 'classid' )->queryAll ();
			
			$sql = "update class set studentsnum=:studentsnum where classid=:classid and schoolid=:schoolid";
			$command = Yii::app ()->db->createCommand ( $sql );
			
			$sum = 0;
			foreach ( $rows as $row ) {
				$command->bindParam ( ":studentsnum", $row ['cnt'], PDO::PARAM_INT );
				$command->bindParam ( ":classid", $row ['classid'], PDO::PARAM_STR );
				$command->bindParam ( ":schoolid", $schoolid, PDO::PARAM_STR );
				$command->execute ();
				$sum += intval ( $row ['cnt'] );
			}
			
			$classnum = Yii::app ()->db->createCommand ()->select ( 'count(*) as cnt' )->from ( 'class' )->where ( 'schoolid=:schoolid and status=0', array (':schoolid' => $schoolid ) )->queryScalar ();
			
			$sql = "update school set studentsnum=:studentsnum,classnum=:classnum where schoolid=:schoolid";
			$command = Yii::app ()->db->createCommand ( $sql );
			$command->bindParam ( ":studentsnum", $sum, PDO::PARAM_INT );
			$command->bindParam ( ":classnum", $classnum, PDO::PARAM_INT );
			$command->bindParam ( ":schoolid", $schoolid, PDO::PARAM_STR );
			$command->execute ();
		}
		
		if ($type == 'staff' || $type == '') {
			$cnt = Yii::app ()->db->createCommand ()->select ( 'count(*) as cnt' )->from ( 'staff' )->where ( 'schoolid=:schoolid', array (':schoolid' => $schoolid ) )->queryScalar ();
			
			$sql = "update school set staffnum=:staffnum where schoolid=:schoolid";
			$command = Yii::app ()->db->createCommand ( $sql );
			$command->bindParam ( ":staffnum", $cnt, PDO::PARAM_INT );
			$command->bindParam ( ":schoolid", $schoolid, PDO::PARAM_STR );
			$command->execute ();
		}
	}
	
	/**
	 * 下载模板文件
	 * @return <type>
	 */
	public static function Download($file_path, $show_name, $extend = 'xml') {
		//        $filename = trim($_REQUEST['filename']);
		//        $showfilename = trim($_REQUEST['showfilename']);
		//        $fileDir = Yii::app()->params['stu_template_path'] . $filename . '.xls';
		//文件不存在
		if (file_exists ( $file_path ) == false) {
			header ( "Content-type:text/html;charset=utf-8" );
			echo "<script>alert('您要下载的文件不存在！');</script>";
			return;
		}
		$ua = $_SERVER ["HTTP_USER_AGENT"];
		$encoded_filename = urlencode ( $show_name );
		$encoded_filename = str_replace ( "+", "%20", $encoded_filename );
		header ( 'Content-Type: application/octet-stream' );
		if (preg_match ( "/MSIE/", $ua )) {
			header ( 'Content-Disposition: attachment; filename="' . $encoded_filename . '.' . $extend . '"' );
		} else if (preg_match ( "/Firefox/", $ua )) {
			header ( 'Content-Disposition: attachment; filename*="utf8\'\'' . $show_name . '.' . $extend . '"' );
		} else {
			header ( 'Content-Disposition: attachment; filename="' . $show_name . '.' . $extend . '"' );
		}
		header ( 'Content-Length:' . filesize ( $file_path ) );
		header ( 'Cache-Control:must-revalidate,post-check=0,pre-check=0' );
		header ( 'Expires:0' );
		header ( 'Pragma:public' );
		ob_clean ();
		flush ();
		readfile ( $file_path );
	
	}
	
	public static function VisitDocTemp() {
		$head = <<<head
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<?mso-application progid="Word.Document"?>
<w:wordDocument xmlns:aml="http://schemas.microsoft.com/aml/2001/core" xmlns:dt="uuid:C2F41010-65B3-11d1-A29F-00AA00C14882" xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.microsoft.com/office/word/2003/wordml" xmlns:wx="http://schemas.microsoft.com/office/word/2003/auxHint" xmlns:wsp="http://schemas.microsoft.com/office/word/2003/wordml/sp2" xmlns:sl="http://schemas.microsoft.com/schemaLibrary/2003/core" xmlns:ns0="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" w:macrosPresent="no" w:embeddedObjPresent="no" w:ocxPresent="no" xml:space="preserve"><w:ignoreSubtree w:val="http://schemas.microsoft.com/office/word/2003/wordml/sp2"/><o:DocumentProperties><o:Author>User</o:Author><o:LastAuthor>User</o:LastAuthor><o:Revision>350</o:Revision><o:TotalTime>100</o:TotalTime><o:LastPrinted>2012-11-13T05:13:00Z</o:LastPrinted><o:Created>2012-11-06T09:16:00Z</o:Created><o:LastSaved>2012-11-13T06:33:00Z</o:LastSaved><o:Pages>2</o:Pages><o:Words>12</o:Words><o:Characters>73</o:Characters><o:Company>China</o:Company><o:Lines>1</o:Lines><o:Paragraphs>1</o:Paragraphs><o:CharactersWithSpaces>84</o:CharactersWithSpaces><o:Version>12</o:Version></o:DocumentProperties><w:fonts><w:defaultFonts w:ascii="Times New Roman" w:fareast="宋体" w:h-ansi="Times New Roman" w:cs="Times New Roman"/><w:font w:name="Times New Roman"><w:panose-1 w:val="02020603050405020304"/><w:charset w:val="00"/><w:family w:val="Roman"/><w:pitch w:val="variable"/><w:sig w:usb-0="E0002AFF" w:usb-1="C0007841" w:usb-2="00000009" w:usb-3="00000000" w:csb-0="000001FF" w:csb-1="00000000"/></w:font><w:font w:name="宋体"><w:altName w:val="SimSun"/><w:panose-1 w:val="02010600030101010101"/><w:charset w:val="86"/><w:family w:val="auto"/><w:pitch w:val="variable"/><w:sig w:usb-0="00000003" w:usb-1="288F0000" w:usb-2="00000016" w:usb-3="00000000" w:csb-0="00040001" w:csb-1="00000000"/></w:font><w:font w:name="Cambria Math"><w:panose-1 w:val="02040503050406030204"/><w:charset w:val="01"/><w:family w:val="Roman"/><w:notTrueType/><w:pitch w:val="variable"/><w:sig w:usb-0="00000000" w:usb-1="00000000" w:usb-2="00000000" w:usb-3="00000000" w:csb-0="00000000" w:csb-1="00000000"/></w:font><w:font w:name="@宋体"><w:panose-1 w:val="02010600030101010101"/><w:charset w:val="86"/><w:family w:val="auto"/><w:pitch w:val="variable"/><w:sig w:usb-0="00000003" w:usb-1="288F0000" w:usb-2="00000016" w:usb-3="00000000" w:csb-0="00040001" w:csb-1="00000000"/></w:font></w:fonts><w:styles><w:versionOfBuiltInStylenames w:val="7"/><w:latentStyles w:defLockedState="off" w:latentStyleCount="267"><w:lsdException w:name="Normal"/><w:lsdException w:name="heading 1"/><w:lsdException w:name="heading 2"/><w:lsdException w:name="heading 3"/><w:lsdException w:name="heading 4"/><w:lsdException w:name="heading 5"/><w:lsdException w:name="heading 6"/><w:lsdException w:name="heading 7"/><w:lsdException w:name="heading 8"/><w:lsdException w:name="heading 9"/><w:lsdException w:name="caption"/><w:lsdException w:name="Title"/><w:lsdException w:name="Subtitle"/><w:lsdException w:name="Strong"/><w:lsdException w:name="Emphasis"/><w:lsdException w:name="No Spacing"/><w:lsdException w:name="List Paragraph"/><w:lsdException w:name="Quote"/><w:lsdException w:name="Intense Quote"/><w:lsdException w:name="Subtle Emphasis"/><w:lsdException w:name="Intense Emphasis"/><w:lsdException w:name="Subtle Reference"/><w:lsdException w:name="Intense Reference"/><w:lsdException w:name="Book Title"/><w:lsdException w:name="TOC Heading"/></w:latentStyles><w:style w:type="paragraph" w:default="on" w:styleId="a"><w:name w:val="Normal"/><wx:uiName wx:val="正文"/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:h-ansi="宋体" w:cs="宋体"/><wx:font wx:val="宋体"/><w:sz w:val="24"/><w:sz-cs w:val="24"/><w:lang w:val="EN-US" w:fareast="ZH-CN" w:bidi="AR-SA"/></w:rPr></w:style><w:style w:type="character" w:default="on" w:styleId="a0"><w:name w:val="Default Paragraph Font"/><wx:uiName wx:val="默认段落字体"/></w:style><w:style w:type="table" w:default="on" w:styleId="a1"><w:name w:val="Normal Table"/><wx:uiName wx:val="普通表格"/><w:rPr><wx:font wx:val="Times New Roman"/><w:lang w:val="EN-US" w:fareast="ZH-CN" w:bidi="AR-SA"/></w:rPr><w:tblPr><w:tblInd w:w="0" w:type="dxa"/><w:tblCellMar><w:top w:w="0" w:type="dxa"/><w:left w:w="108" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar></w:tblPr></w:style><w:style w:type="list" w:default="on" w:styleId="a2"><w:name w:val="No List"/><wx:uiName wx:val="无列表"/></w:style><w:style w:type="character" w:styleId="a3"><w:name w:val="Hyperlink"/><wx:uiName wx:val="超链接"/><w:rsid w:val="002D1C80"/><w:rPr><w:color w:val="0000FF"/><w:u w:val="single"/></w:rPr></w:style><w:style w:type="character" w:styleId="a4"><w:name w:val="FollowedHyperlink"/><wx:uiName wx:val="已访问的超链接"/><w:rsid w:val="002D1C80"/><w:rPr><w:color w:val="800080"/><w:u w:val="single"/></w:rPr></w:style><w:style w:type="paragraph" w:styleId="a5"><w:name w:val="header"/><wx:uiName wx:val="页眉"/><w:basedOn w:val="a"/><w:link w:val="Char"/><w:rsid w:val="002D1C80"/><w:pPr><w:pBdr><w:bottom w:val="single" w:sz="6" wx:bdrwidth="15" w:space="1" w:color="auto"/></w:pBdr><w:tabs><w:tab w:val="center" w:pos="4153"/><w:tab w:val="right" w:pos="8306"/></w:tabs><w:snapToGrid w:val="off"/><w:jc w:val="center"/></w:pPr><w:rPr><w:rFonts w:cs="Times New Roman" w:hint="fareast"/><wx:font wx:val="宋体"/><w:sz w:val="18"/><w:sz-cs w:val="18"/><w:lang/></w:rPr></w:style><w:style w:type="character" w:styleId="Char"><w:name w:val="页眉 Char"/><w:link w:val="a5"/><w:locked/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:fareast="宋体" w:h-ansi="宋体" w:cs="宋体" w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:style><w:style w:type="paragraph" w:styleId="a6"><w:name w:val="footer"/><wx:uiName wx:val="页脚"/><w:basedOn w:val="a"/><w:link w:val="Char0"/><w:rsid w:val="002D1C80"/><w:pPr><w:tabs><w:tab w:val="center" w:pos="4153"/><w:tab w:val="right" w:pos="8306"/></w:tabs><w:snapToGrid w:val="off"/></w:pPr><w:rPr><w:rFonts w:cs="Times New Roman" w:hint="fareast"/><wx:font wx:val="宋体"/><w:sz w:val="18"/><w:sz-cs w:val="18"/><w:lang/></w:rPr></w:style><w:style w:type="character" w:styleId="Char0"><w:name w:val="页脚 Char"/><w:link w:val="a6"/><w:locked/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:fareast="宋体" w:h-ansi="宋体" w:cs="宋体" w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:style><w:style w:type="paragraph" w:styleId="a7"><w:name w:val="Balloon Text"/><wx:uiName wx:val="批注框文本"/><w:basedOn w:val="a"/><w:link w:val="Char1"/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:cs="Times New Roman" w:hint="fareast"/><wx:font wx:val="宋体"/><w:sz w:val="18"/><w:sz-cs w:val="18"/><w:lang/></w:rPr></w:style><w:style w:type="character" w:styleId="Char1"><w:name w:val="批注框文本 Char"/><w:link w:val="a7"/><w:locked/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:fareast="宋体" w:h-ansi="宋体" w:cs="宋体" w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:style><w:style w:type="table" w:styleId="a8"><w:name w:val="Table Grid"/><wx:uiName wx:val="网格型"/><w:basedOn w:val="a1"/><w:rsid w:val="002D1C80"/><w:rPr><wx:font wx:val="Times New Roman"/></w:rPr><w:tblPr><w:tblInd w:w="0" w:type="dxa"/><w:tblBorders><w:top w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:insideH w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:insideV w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/></w:tblBorders><w:tblCellMar><w:top w:w="0" w:type="dxa"/><w:left w:w="108" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar></w:tblPr></w:style></w:styles><w:shapeDefaults><o:shapedefaults v:ext="edit" spidmax="16386"/><o:shapelayout v:ext="edit"><o:idmap v:ext="edit" data="1"/></o:shapelayout></w:shapeDefaults><w:docPr><w:view w:val="print"/><w:zoom w:percent="120"/><w:doNotEmbedSystemFonts/><w:bordersDontSurroundHeader/><w:bordersDontSurroundFooter/><w:proofState w:spelling="clean" w:grammar="clean"/><w:defaultTabStop w:val="420"/><w:drawingGridHorizontalSpacing w:val="120"/><w:drawingGridVerticalSpacing w:val="163"/><w:displayHorizontalDrawingGridEvery w:val="2"/><w:displayVerticalDrawingGridEvery w:val="2"/><w:characterSpacingControl w:val="DontCompress"/><w:webPageEncoding w:val="unicode"/><w:optimizeForBrowser/><w:validateAgainstSchema/><w:saveInvalidXML w:val="off"/><w:ignoreMixedContent w:val="off"/><w:alwaysShowPlaceholderText w:val="off"/><w:hdrShapeDefaults><o:shapedefaults v:ext="edit" spidmax="16386"/></w:hdrShapeDefaults><w:footnotePr><w:footnote w:type="separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:separator/></w:r></w:p></w:footnote><w:footnote w:type="continuation-separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:continuationSeparator/></w:r></w:p></w:footnote></w:footnotePr><w:endnotePr><w:endnote w:type="separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:separator/></w:r></w:p></w:endnote><w:endnote w:type="continuation-separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:continuationSeparator/></w:r></w:p></w:endnote></w:endnotePr><w:compat><w:breakWrappedTables/><w:snapToGridInCell/><w:wrapTextWithPunct/><w:useAsianBreakRules/><w:dontGrowAutofit/><w:useFELayout/></w:compat><wsp:rsids><wsp:rsidRoot wsp:val="00BC5206"/><wsp:rsid wsp:val="0000354B"/><wsp:rsid wsp:val="000C0E2F"/><wsp:rsid wsp:val="000E422E"/><wsp:rsid wsp:val="000E6367"/><wsp:rsid wsp:val="000F54B6"/><wsp:rsid wsp:val="00107C83"/><wsp:rsid wsp:val="00116C4A"/><wsp:rsid wsp:val="0014104D"/><wsp:rsid wsp:val="00174635"/><wsp:rsid wsp:val="00176D84"/><wsp:rsid wsp:val="00181050"/><wsp:rsid wsp:val="00195BE3"/><wsp:rsid wsp:val="001A327E"/><wsp:rsid wsp:val="001D325E"/><wsp:rsid wsp:val="001E6BEF"/><wsp:rsid wsp:val="001F0474"/><wsp:rsid wsp:val="00211B9C"/><wsp:rsid wsp:val="00250603"/><wsp:rsid wsp:val="00277027"/><wsp:rsid wsp:val="0028265D"/><wsp:rsid wsp:val="002A1028"/><wsp:rsid wsp:val="002A1100"/><wsp:rsid wsp:val="002B44EF"/><wsp:rsid wsp:val="002D1C80"/><wsp:rsid wsp:val="002D2D14"/><wsp:rsid wsp:val="002D30F3"/><wsp:rsid wsp:val="002E3C7F"/><wsp:rsid wsp:val="0031436E"/><wsp:rsid wsp:val="00314D7D"/><wsp:rsid wsp:val="00325C72"/><wsp:rsid wsp:val="00332548"/><wsp:rsid wsp:val="003345C3"/><wsp:rsid wsp:val="00350D8B"/><wsp:rsid wsp:val="00354D18"/><wsp:rsid wsp:val="00393107"/><wsp:rsid wsp:val="003B4525"/><wsp:rsid wsp:val="003C6848"/><wsp:rsid wsp:val="003D60C5"/><wsp:rsid wsp:val="00445F97"/><wsp:rsid wsp:val="00454D71"/><wsp:rsid wsp:val="00455081"/><wsp:rsid wsp:val="0048361E"/><wsp:rsid wsp:val="004F03C8"/><wsp:rsid wsp:val="00514B65"/><wsp:rsid wsp:val="00520BE8"/><wsp:rsid wsp:val="00520DCA"/><wsp:rsid wsp:val="00575205"/><wsp:rsid wsp:val="00576B5E"/><wsp:rsid wsp:val="0058588A"/><wsp:rsid wsp:val="005C366A"/><wsp:rsid wsp:val="006312C1"/><wsp:rsid wsp:val="00631A45"/><wsp:rsid wsp:val="00631D06"/><wsp:rsid wsp:val="0066489E"/><wsp:rsid wsp:val="00672486"/><wsp:rsid wsp:val="006835E1"/><wsp:rsid wsp:val="00687174"/><wsp:rsid wsp:val="00694F4F"/><wsp:rsid wsp:val="006D4F3D"/><wsp:rsid wsp:val="0070290C"/><wsp:rsid wsp:val="007111C5"/><wsp:rsid wsp:val="00716C80"/><wsp:rsid wsp:val="0072094D"/><wsp:rsid wsp:val="00725351"/><wsp:rsid wsp:val="007400A6"/><wsp:rsid wsp:val="00745FB3"/><wsp:rsid wsp:val="00772766"/><wsp:rsid wsp:val="00777992"/><wsp:rsid wsp:val="00824F8E"/><wsp:rsid wsp:val="00830546"/><wsp:rsid wsp:val="00830FCD"/><wsp:rsid wsp:val="0085243E"/><wsp:rsid wsp:val="00863CC4"/><wsp:rsid wsp:val="00870A77"/><wsp:rsid wsp:val="008B146D"/><wsp:rsid wsp:val="008E00F9"/><wsp:rsid wsp:val="008E7994"/><wsp:rsid wsp:val="008F570E"/><wsp:rsid wsp:val="009307BB"/><wsp:rsid wsp:val="00942364"/><wsp:rsid wsp:val="0099674D"/><wsp:rsid wsp:val="009D7E68"/><wsp:rsid wsp:val="009F403C"/><wsp:rsid wsp:val="00A06056"/><wsp:rsid wsp:val="00A22638"/><wsp:rsid wsp:val="00A34021"/><wsp:rsid wsp:val="00A47DEE"/><wsp:rsid wsp:val="00A61853"/><wsp:rsid wsp:val="00A74AE6"/><wsp:rsid wsp:val="00AD541A"/><wsp:rsid wsp:val="00AD7097"/><wsp:rsid wsp:val="00AE533D"/><wsp:rsid wsp:val="00AE5B4A"/><wsp:rsid wsp:val="00AE70DA"/><wsp:rsid wsp:val="00B21D03"/><wsp:rsid wsp:val="00B227CE"/><wsp:rsid wsp:val="00B5642B"/><wsp:rsid wsp:val="00B57668"/><wsp:rsid wsp:val="00B70A9B"/><wsp:rsid wsp:val="00BC5206"/><wsp:rsid wsp:val="00C034A1"/><wsp:rsid wsp:val="00C16E91"/><wsp:rsid wsp:val="00C33B51"/><wsp:rsid wsp:val="00C45DC7"/><wsp:rsid wsp:val="00C52264"/><wsp:rsid wsp:val="00C80BE2"/><wsp:rsid wsp:val="00C81DB6"/><wsp:rsid wsp:val="00C90792"/><wsp:rsid wsp:val="00CC1C18"/><wsp:rsid wsp:val="00CD0BE6"/><wsp:rsid wsp:val="00D253C2"/><wsp:rsid wsp:val="00D6107F"/><wsp:rsid wsp:val="00D86D35"/><wsp:rsid wsp:val="00DC18C6"/><wsp:rsid wsp:val="00DC2C10"/><wsp:rsid wsp:val="00DC3C49"/><wsp:rsid wsp:val="00DD453F"/><wsp:rsid wsp:val="00DD464D"/><wsp:rsid wsp:val="00E4788F"/><wsp:rsid wsp:val="00EB1464"/><wsp:rsid wsp:val="00ED0357"/><wsp:rsid wsp:val="00EE1961"/><wsp:rsid wsp:val="00EF22D5"/><wsp:rsid wsp:val="00F208B7"/><wsp:rsid wsp:val="00F23C7F"/><wsp:rsid wsp:val="00F83B97"/><wsp:rsid wsp:val="00F86105"/><wsp:rsid wsp:val="00FA6B08"/><wsp:rsid wsp:val="00FF4353"/></wsp:rsids></w:docPr><w:body>
head;
		$body = <<<body
<w:p wsp:rsidR="00454D71" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:b/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r wsp:rsidRPr="00A74AE6"><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>回访编号：</w:t></w:r><w:proofErr w:type="gramStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>num</w:t></w:r><w:proofErr w:type="gramEnd"/></w:p><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="118" w:left="283" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:rPr><w:sz w:val="16"/><w:sz-cs w:val="16"/></w:rPr></w:pPr></w:p><w:tbl><w:tblPr><w:tblW w:w="9663" w:type="dxa"/><w:jc w:val="center"/><w:tblLayout w:type="Fixed"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="613"/><w:gridCol w:w="5478"/><w:gridCol w:w="3572"/></w:tblGrid><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="404"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge w:val="restart"/><w:textFlow w:val="tb-rl-v"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:ind w:left="113" w:right="113"/><w:jc w:val="center"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtname</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="0014104D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtid</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="409"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="700" w:first-line="1050"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="15"/><w:sz-cs w:val="15"/></w:rPr><w:t>mchtaddr</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>contactor</w:t></w:r></w:p></w:tc></w:tr><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="399"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>rep</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>inter_time</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="402"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>account</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>tel</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr></w:tbl><w:p wsp:rsidR="003C6848" wsp:rsidRPr="000C0E2F" wsp:rsidRDefault="003C6848" wsp:rsidP="000C0E2F"/>
body;
		$body_first = <<<bfi
<w:p wsp:rsidR="00AD7097" wsp:rsidRDefault="00AD7097" wsp:rsidP="00824F8E"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00332548" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="00332548" wsp:rsidP="00745FB3"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:b/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r wsp:rsidRPr="00A74AE6"><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>回访编号：</w:t></w:r><w:proofErr w:type="gramStart"/><w:r wsp:rsidR="00EB1464"><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>num</w:t></w:r><w:proofErr w:type="gramEnd"/></w:p><w:p wsp:rsidR="00AD7097" wsp:rsidRDefault="00AD7097" wsp:rsidP="00824F8E"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00A74AE6" wsp:rsidRDefault="00A74AE6" wsp:rsidP="00A74AE6"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="118" w:left="283" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="001D325E" wsp:rsidRDefault="001D325E"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:rPr><w:sz w:val="16"/><w:sz-cs w:val="16"/></w:rPr></w:pPr></w:p><w:tbl><w:tblPr><w:tblW w:w="9663" w:type="dxa"/><w:jc w:val="center"/><w:tblLayout w:type="Fixed"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="613"/><w:gridCol w:w="5478"/><w:gridCol w:w="3572"/></w:tblGrid><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="404"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge w:val="restart"/><w:textFlow w:val="tb-rl-v"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:ind w:left="113" w:right="113"/><w:jc w:val="center"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="0085243E" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtname</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="0014104D" wsp:rsidRDefault="00250603" wsp:rsidP="0014104D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtid</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="409"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="00250603" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="700" w:first-line="1050"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="15"/><w:sz-cs w:val="15"/></w:rPr><w:t>mchtaddr</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>contactor</w:t></w:r></w:p></w:tc></w:tr><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="399"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>rep</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>inter_time</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="402"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>account</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00C52264" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>tel</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr></w:tbl><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="003B4C75"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/></w:pPr></w:p>
bfi;
		$foot = <<<foot
<w:sectPr wsp:rsidR="003C6848" wsp:rsidSect="00824F8E"><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="851" w:right="851" w:bottom="851" w:left="851" w:header="851" w:footer="992" w:gutter="0"/><w:cols w:space="425"/><w:docGrid w:type="lines" w:line-pitch="326"/></w:sectPr></w:body></w:wordDocument>
foot;
		$page = <<<page
<w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr><w:r><w:br w:type="page"/></w:r></w:p>
page;
		
		return array ('head' => $head, 'body' => $body, 'body_first' => $body_first, 'foot' => $foot, 'page' => $page );
	}
	
	/**
	 * @param array $file $_FILE数组
	 * @param array $type 允许上传的文件格式
	 * @param string $dir 文件存储路径  eg:../files/coupon
	 *
	 * @return array 上传文件处理返回说明：
	 * status:文件上传状态 1:成功上传，-1:上传失败或者格式、大小错误
	 * desc:上传结果描述
	 * path:文件存储路径(要存到DB的相对路径)
	 * upname:上传文件原名称
	 * savename:存储文件名称
	 * @author yangtl
	 */
	
	public static function fileUpload($file) {
		$return = array ();

		$full_dir = Yii::app ()->params ['upload_file_path'];
		$fname = $file ['name'];

        $fileTypes = array('jpg','jpeg','gif','png','mp4'); // File extensions
        $fileParts = pathinfo($fname);

		$ftype = substr ( strrchr ( $fname, '.' ), 1 );
		if ($file ['error'] > 0) {
			$return ['status'] = '-2';
			$return ['desc'] = '上传文件失败';
		} elseif (empty( $fname )) {
			$return ['status'] = '-3';
			$return ['desc'] = '没有上传任何文件';
		} elseif ($file ['size'] > (Yii::app ()->params ['upload_file_maxsize']) * 1024 * 1024) {
			$return ['status'] = '-4';
			$return ['desc'] = '上传文件太大了';
		} elseif ($file ['size'] == 0) {
			$return ['status'] = '-5';
			$return ['desc'] = '上传文件不能为空';
		}elseif (!in_array($fileParts['extension'],$fileTypes)) {
            $return ['status'] = '-6';
            $return ['desc'] = '上传文件格式不正确！';
        } else {
			$rname = time().rand(0,1000);
			$dname = $rname . '.' . $ftype;
			if (! is_dir ( $full_dir )) {
				umask ( 0000 );
				mkdir ( $full_dir, 0777, true );
			}
            $path = $full_dir . '/' . $dname;
            if (move_uploaded_file ( $file['tmp_name'], $path )) {
                $return ['status'] = '1';
                $return ['desc'] = '文件上传成功';
                $return ['upname'] = $fname;
                $return ['savename'] = $dname;
                $return ['url'] = "./images/attach/".$dname;
                $return ['type'] = $ftype;
            } else {
                $return ['status'] = '-7';
                $return ['desc'] = '服务器繁忙，上传失败';
            }
		}
		return $return;
	}
	
	/**
	 * DB设计不自增的字段，计算最新值
	 * @param string $table 表格
	 * @param string $column 需要计算的列
	 * @return number 最新的列值
	 * @author yangtl
	 */
	public static function newId($table, $column) {
		$max = $rows = Yii::app ()->db->createCommand ()->select ( "max({$column}) max" )->from ( $table )->queryRow ();
		$max = $max ['max'];
		
		if ($max)
			$new_cardtype = $max + 1;
		else
			$new_cardtype = 1;
		
		return $new_cardtype;
	
	}
	
	/**
	 * 获取汉字拼音首字母
	 * @param $str string 一个汉字
	 * @param $codetype string 字符串编码方式
	 * @return string 拼音首字母
	 * @author yangtl
	 */
	public static function getfirstchar($str, $codetype = 'UTF-8') {
		$str = trim($str);
		$str = mb_substr($str, 0,1,$codetype);  //第一个字
		$ret = "";
		if ($codetype == 'UTF-8'){
			$str = mb_convert_encoding($str, "gb2312","UTF-8");
		}
		$s1 = substr ( $str, 0, 1 );
		$fchar = ord ( $s1 );  
		if ($fchar < 160) { //非中文
			if ($fchar >= ord ( "A" ) and $fchar <= ord ( "z" )) //字母
				return strtoupper ( $s1 );
			else if ($fchar >= 48 && $fchar <= 57) //数字
				return $s1;
			
		} else { //中文
			$asc = ord ( $str [0] ) * 256 + ord ( $str [1] ) - 65536;
			if ($asc >= - 20319 and $asc <= - 20284)
				return "A";
			if ($asc >= - 20283 and $asc <= - 19776)
				return "B";
			if ($asc >= - 19775 and $asc <= - 19219)
				return "C";
			if ($asc >= - 19218 and $asc <= - 18711)
				return "D";
			if ($asc >= - 18710 and $asc <= - 18527)
				return "E";
			if ($asc >= - 18526 and $asc <= - 18240)
				return "F";
			if ($asc >= - 18239 and $asc <= - 17923)
				return "G";
			if ($asc >= - 17922 and $asc <= - 17418)
				return "H";
			if ($asc >= - 17417 and $asc <= - 16475)
				return "J";
			if ($asc >= - 16474 and $asc <= - 16213)
				return "K";
			if ($asc >= - 16212 and $asc <= - 15641)
				return "L";
			if ($asc >= - 15640 and $asc <= - 15166)
				return "M";
			if ($asc >= - 15165 and $asc <= - 14923)
				return "N";
			if ($asc >= - 14922 and $asc <= - 14915)
				return "O";
			if ($asc >= - 14914 and $asc <= - 14631)
				return "P";
			if ($asc >= - 14630 and $asc <= - 14150)
				return "Q";
			if ($asc >= - 14149 and $asc <= - 14091)
				return "R";
			if ($asc >= - 14090 and $asc <= - 13319)
				return "S";
			if ($asc >= - 13318 and $asc <= - 12839)
				return "T";
			if ($asc >= - 12838 and $asc <= - 12557)
				return "W";
			if ($asc >= - 12556 and $asc <= - 11848)
				return "X";
			if ($asc >= - 11847 and $asc <= - 11056)
				return "Y";
			if ($asc >= - 11055 and $asc <= - 10247)
				return "Z";
		}
		
		$oter_zh = self::_otherChinese ();
		if (array_key_exists ( $str, $oter_zh )) {
			return $oter_zh [$str];
		}
		
		return '~';
	}
	
	//ascii编码不在上面的汉字
	private static function _otherChinese() {
		$rs = array (iconv ( "UTF-8", "gb2312", "茉" ) => "M", 
				iconv ( "UTF-8", "gb2312", "缇" ) => "T",
				iconv ( "UTF-8", "gb2312", "鑫" ) => "X", 
				iconv ( "UTF-8", "gb2312", "闫" ) => "Y",
				iconv ( "UTF-8", "gb2312", "味" ) => "W",
			);
		return $rs;
	
	}
	
	/**
	 * 保存缩略图
	 * @param $dst_width 目标宽度
	 * @param $dst_height 目标高度
	 * @param $res 原图片 eg:/usr/files/123.png
	 * @param $dst_path 目标图片路径 eg:/usr/files/s
	 * @param $res_x 截取原图的x坐标，默认0对整个图片缩放
	 * @param $res_y 截取原图的y坐标，默认0对整个图片缩放
	 * @param $res_w 截取原图的宽度，默认对整个图片缩放
	 * @param $res_h 截取原图的高度，默认对整个图片缩放
	 * @author yangtl
	 */
	
	public static function resizeimg($dst_width, $dst_height, $res, $dst_path, $res_x = 0, $res_y = 0, $res_w = '', $res_h = '') {
		$resizeimg = new ResizeImage ();
		$path_flag_pos = strrpos ( $res, '/' );
		$res_path = substr ( $res, 0, $path_flag_pos );
		$res_fullname = substr ( $res, $path_flag_pos + 1 );
		$type_flag_pos = strrpos ( $res_fullname, '.' );
		$res_name = substr ( $res_fullname, 0, $type_flag_pos );
		$res_type = substr ( $res_fullname, $type_flag_pos + 1 );
		
		$img_size = getimagesize($res);
		$img_w = $img_size[0];
		$img_h = $img_size[1];
		
		$resizeimg->max_width = $dst_width;
		$resizeimg->max_height = $dst_height;

		$resizeimg->res_path = $res_path;
		$resizeimg->res_name = $res_name;
		$resizeimg->res_type = $res_type;
		$resizeimg->dst_path = $dst_path;
		$resizeimg->dst_name = $res_name;
		$resizeimg->dst_type = $res_type;
		$resizeimg->res_x = $res_x;
		$resizeimg->res_y = $res_y;
		$resizeimg->res_w = $res_w;
		$resizeimg->res_h = $res_h;
		
		$rs = $resizeimg->runResize ();
		
		return $rs;
	
	}
	
	public static function mbsub($str,$len) {
        if(mb_strlen($str)>$len){
            return "<span title='{$str}'>" . mb_substr ( $str,0,$len, "UTF-8" ) . "...</span>";
        }else{
            return $str;
        }
	}
	
	//从文件的路径得到文件的名称、类型
	public static function getFileMsg($filepath) {
		$pos1 = strrpos ( $filepath, "/" );
		$filename = substr ( $filepath, $pos1 + 1 );
		$pos2 = strrpos ( $filename, '.' );
		$filetype = substr ( $filename, $pos2 + 1 );
		return array ('filename' => $filename, 'filetype' => $filetype );
	
	}

    public static function G_Id($suffix=""){
        return date("YmdHis").rand(1000,9999).$suffix;
    }

}
<?php defined('SYSPATH') OR die('No direct script access.');

require APPPATH.'classes/PHPExcel/PHPExcel'.EXT;
require APPPATH.'classes/PHPMailer/PHPMailerAutoload'.EXT;

class Util {
	public static function sendEmail($emails, $title, $text, $isHtml = true) {
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = SMTPHost;
		$mail->Username = SMTPUser;
		$mail->Password = SMTPPass;

		$mail->From = MailFrom;
		$mail->FromName = MailFromName;
		$mail->CharSet = "UTF-8";
		$mail->IsHTML($isHtml);

		if (is_array($emails))
			foreach($emails as $email) $mail->addAddress($email);
		else
			$mail->addAddress($emails);

		$mail->Subject = $title;
		$mail->Body    = $text;

		if(!$mail->send()) {
		    Util::SmallLog('mail', $mail->ErrorInfo);
		    return false;
		} else {
		    return true;
		}
	}
	public static function getAllWeekByYear($year) {
		$year_start = $year . "-01-01";
		$year_end = $year . "-12-31";
		$startday = strtotime($year_start);
		if (intval(date('N', $startday)) != '1') $startday = strtotime("next monday", strtotime($year_start)); //获取年第一周的日期
		$year_mondy = date("Y-m-d", $startday); //获取年第一周的日期

		$endday = strtotime($year_end);
		if (intval(date('W', $endday)) == '7') $endday = strtotime("last sunday", strtotime($year_end));

		$num = intval(date('W', $endday));
		for ($i = 1; $i <= $num; $i++) {
			$j = $i -1;
			$start_date = date("Y-m-d", strtotime("$year_mondy $j week "));
			$end_day = date("Y-m-d", strtotime("$start_date +6 day"));
			$week_array[$i] = array (str_replace("-",".",$start_date), str_replace("-", ".", $end_day));
		}
		return $week_array;
	}
	public static function getAge($b){
		if (!$b) return '';
		$b = strtotime($b);
		$age = intval((TIMESTAMP - $b)/(3600*24*365));
		if ($age == 0) return '';
		return $age.'岁';
	}
	public static function exportXls($name, $data, $filename = 'report.csv', $merge = array(), $width = array()) {
		$phpExcel = new PHPExcel();
        $activeSheet = $phpExcel->setActiveSheetIndex(0);
        $activeSheet->setTitle($name);

        foreach ($data as $k1 => $v1) {
        	foreach ($v1 as $k2 => $v2) $activeSheet->setCellValueByColumnAndRow($k2, $k1+1, $v2);
        }
	 	if (count($data)>0) foreach ($width as $k2=>$v2) $activeSheet->getColumnDimensionByColumn($k2)->setWidth($v2);
        foreach ($merge as $v) {
        	$activeSheet->mergeCellsByColumnAndRow($v[0], $v[1], $v[2], $v[3]);
        	$activeSheet->getStyleByColumnAndRow($v[0], $v[1])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        	if ($v[1] != $v[3])
        		$activeSheet->getStyleByColumnAndRow($v[0], $v[1])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}

		$objWriter = new PHPExcel_Writer_Excel5($phpExcel); //PHPExcel_Writer_Excel5/PHPExcel_Writer_Excel2007
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header("Content-Transfer-Encoding:binary");
		header("Pragma: public");
		header("Expires: 0");
		$ua = $_SERVER["HTTP_USER_AGENT"];
		header('Content-Type: text/csv; charset=utf-8');
		if (preg_match("/MSIE/", $ua))
			header('Content-Disposition: attachment; filename="' .str_replace("+", "%20", urlencode($filename)). '"');
		else if (preg_match("/Firefox/", $ua))
			header('Content-Disposition: attachment; filename*="utf8\'\'' .$filename. '"');
		else
			header('Content-Disposition: attachment;filename="' .$filename. '"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}
	public static function isMobile() {
		$userAgent = $_SERVER['HTTP_USER_AGENT'] ?: '';
		if (!$userAgent) return false;
		$result = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino|ipad/i', $userAgent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($userAgent, 0, 4)));
		return $result ? 1 : 0;
	}
	public static function formatTime($sTime) {
		if (!$sTime) return '';
		$cTime      =   time();
		$dTime      =   $cTime - $sTime;
		$dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
		$dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));

		if( $dTime < 60 ) return $dTime.'秒前';
		elseif( $dTime < 3600 )	return intval($dTime/60).'分钟前';
		elseif( $dTime >= 3600 && $dDay == 0  )	return intval($dTime/3600).'小时前';
		elseif( $dDay > 0 && $dDay<=7 )	return intval($dDay).'天前';
		elseif( $dDay > 7 &&  $dDay <= 30 )	return intval($dDay/7).'周前';
		else return date("Y-m-d H:i:s",$sTime);
	}
	public static function getIco($ico){
		if (!$ico) return '';
		return RESOURCE.'upload/ico/'.$ico;
	}
	public static function getAvatar($avatar){
		if (!$avatar) return '';
		if (Util::startsWith($avatar, 'http')) return $avatar;
		return RESOURCE.'upload/avatar/'.$avatar;
	}
	public static function getIp2Address($ip) {
		$city = self::getTaobaoData($ip);
		if (!$city) $city = self::getTaobaoData2($ip);
		if (!$city) $city = self::getSinaData($ip);
		if (!$city) $city = self::getIpipData($ip);
		if (!$city) $city = self::getBaiduData($ip);
		//if (!$city) $city = self::getQQWryData($ip);
		return $city;
	}
	public static function getQQWryData($ip) {
		$city = array('country'=>IpLocation::getAddressByIP($ip), 'region'=>'', 'city'=>'', 'county'=>'');
		return $address;
	}
	public static function getTaobaoData($ip) {
		$data = self::curl("http://ip.taobao.com/service/getIpInfo.php?ip=$ip");
		if (!empty($data) && Util::startsWith($data, '{')) {
			$data = json_decode($data, true);
			if ($data['code'] == 0) {
				$city = array('country'=>@$data['data']['country'], 'region'=>@$data['data']['region'], 'city'=>@$data['data']['city'], 'county'=>@$data['data']['county']);
				//var_dump($data);
				return $city;
			}
		}
		return false;
	}
	public static function getTaobaoData2($ip) {
		$data = self::curl("http://ip.taobao.com/service/getIpInfo2.php?ip=$ip");
		if (!empty($data) && Util::startsWith($data, '{')) {
			$data = json_decode($data, true);
			if ($data['code'] == 0) {
				$city = array('country'=>@$data['data']['country'], 'region'=>@$data['data']['region'], 'city'=>@$data['data']['city'], 'county'=>@$data['data']['county']);
				//var_dump($data);
				return $city;
			}
		}
		return false;
	}
	public static function getBaiduData($ip) {
		$data = self::curl("http://api.map.baidu.com/location/ip?ak=F454f8a5efe5e577997931cc01de3974&ip=$ip");
		if (!empty($data) && Util::startsWith($data, '{')) {
			$data = json_decode($data, true);
			//var_dump($data);exit;
			if ($data && $data['content'] && $data['content']['address_detail']) {
				$data = $data['content']['address_detail'];
				//var_dump($data);exit;
				$city = array('country'=>'', 'region'=>@$data['province'], 'city'=>@$data['city'], 'county'=>@$data['district']);
				//var_dump($data);
				return $city;
			}
		}
		return false;
	}
	public static function getSinaData($ip) {
		$data = self::curl("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=$ip");
		if ($data) {
			$data = str_replace('var remote_ip_info = {', '{', $data);
			$data = str_replace('};', '}', $data);
		}
		//var_dump($data);exit;
		if (!empty($data) && Util::startsWith($data, '{')) {
			$data = json_decode($data, true);
			//var_dump($data);exit;
			if ($data && $data['ret'] == 1) {
				//var_dump($data);exit;
				$city = array('country'=>@$data['country'], 'region'=>@$data['province'], 'city'=>@$data['city'], 'county'=>@$data['district']);
				//var_dump($data);
				return $city;
			}
		}
		return false;
	}
	public static function getIpipData($ip) {
		$data = self::curl("http://freeapi.ipip.net/$ip");
		//var_dump($data);exit;
		if (!empty($data) && Util::startsWith($data, '[')) {
			$data = json_decode($data, true);
			//var_dump($data);exit;
			if ($data) {
				$city = array('country'=>@$data[0], 'region'=>@$data[1], 'city'=>@$data[2], 'county'=>@$data[3]);
				//var_dump($data);
				return $city;
			}
		}
		return false;
	}
	public static function getPathFiles($path){
		$data = array();
		$dh = opendir($path);
		if ($dh) {
			while (($file = readdir($dh)) !== false) {
				if($file != '.' AND $file != '..' AND $file != '.DS_Store' AND is_file($path.$file)) {
					$data[] = $file;
				}
			}
			closedir($dh);
		}
		return $data;
	}
	public static function getStatus($status) {
		if ($status == 0) return '<span class="red">不可用</span>';
		if ($status == 1) return '<span class="green">可用</span>';
		return "";
	}
	public static function p3p() {
		header( 'P3P: CP="CAO DSP COR CUR ADM DEV TAI PSA PSD IVAi IVDi CONi TELo OTPi OUR DELi SAMi OTRi UNRi PUBi IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE GOV"' );
	}
	public static function getNowUrl() {
		$nowurl = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
		$nowurl = ROOTURL.$nowurl;
		return $nowurl;
	}
	public static function killBadChar($str){
		preg_match_all('/[0-9a-zA-Z \x{4e00}-\x{9fff}]+/u', $str, $matches);
		$str = join('', $matches[0]);
		return trim($str);
	}
	public static function encode36($num) {
		$str = "";
		$typelen = 36;
		$k = str_split ( "ABCDEFGHIJKLMN123456789OPQRSTUVWXYZ0", 1 );
		$num += 10000000;
		while ( $num > 0 ) {
			$char = $num % $typelen;
			$str = $k [$char] . $str;
			$num = ($num - $char) / $typelen;
		}
		return $str;
	}
	public static function filterOnlineUrl($uri) {
		$uri = strtolower($uri);
		if (empty($uri)) return false;
		if (Util::endsWith($uri, '__')) return false;
		if (Util::endsWith($uri, '/main/home')) return false;
		if (Util::endsWith($uri, '/main/index')) return false;
		if (!is_bool(strpos($uri, 'logout'))) return false;
		if (!is_bool(strpos($uri, '__?'))) return false;
		return true;
	}
	public static function startsWith($haystack, $needle) { return $needle === "" || strpos($haystack, $needle) === 0; }
	public static function endsWith($haystack, $needle) { return $needle === "" || substr($haystack, -strlen($needle)) === $needle; }
	public static function validate_email($email) {
		if(!preg_match('/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/', $email)) unset($email);
		return @$email;
	}
	public static function validate_mobile($mobile) {
		if (!is_bool(strpos($mobile, '+'))) return false;
		if (!is_bool(strpos($mobile, ' '))) return false;
		if (!is_bool(strpos($mobile, '('))) return false;
		return is_numeric($mobile) && strlen($mobile) == 11;
	}
	//排序状态
	public static function getOrderStatus($type, $oc, $os) {
		return $oc == $type && $os == "desc" ? "asc" : ($oc == $type && $os == "desc" ? "asc" : "desc");
	}
	//获取语言
	public static function getObjName($obj, $lang = null) {
		if (!isset($obj) || empty($obj)) return '';
		if (is_string($obj) && Util::startsWith($obj, '{')) $obj = json_decode($obj, true);
		if (is_string($obj)) return str_replace('\\', '', $obj);
		if (is_object($obj)) $obj = get_object_vars($obj);
		if (!empty($lang)) return isset($obj[$lang]) ? str_replace('\\', '', $obj[$lang]) : '';

		if (isset($obj['zh_TW'])) return str_replace('\\', '', $obj['zh_TW']);
		if (isset($obj['zh_CN'])) return str_replace('\\', '', $obj['zh_CN']);
		if (isset($obj['en_US'])) return str_replace('\\', '', $obj['en_US']);
		foreach ($obj as $key=>$value) return str_replace('\\', '', $value);
		return '';
	}
	public static function getObjNameAll($obj, $newline = true) {
		if (!isset($obj) || empty($obj)) return '';
		if (is_string($obj) && Util::startsWith($obj, '{')) $obj = json_decode($obj, true);
		if (is_string($obj)) return str_replace('\\', '', $obj);
		if (is_object($obj)) $obj = get_object_vars($obj);

		$html = '';
		foreach ($obj as $key=>$value) $html .= ($newline ? explode('_', $key)[1].'：' : '').str_replace('\\', '', $value).($newline ? '<br />' : '|');
		return trim($html, '|');
	}
	//object to array
	public static function getKeyValue($obj, $key, $delKey = true) {
		$list = array();
		foreach ($obj as $info) {
			$info = get_object_vars($info);
			$id = $info[$key];
			if ($delKey) unset($info[$key]);
			$list[$id] = $info;
		}
		return $list;
	}
	//随机数
	public static function getRandomNum($proArr, $base = 100, $times = 1 ,$default = 0) {
		$result = $default;
		$proSum = $base * $times;
		foreach ( $proArr as $key => $proValue ) {
			$randNum = mt_rand( 1, $proSum );
			if ( $randNum <= $proValue ) {
				$result = $key;
				break;
			} else {
				$proSum -= $proValue;
			}
		}
		return (integer)$result;
	}
	//format
	public static function formatNum($v,$len=4){
		$v=strval($v);
		$n=array();
		$max=ceil(strlen($v)/$len);
		for($i=0;$i<$max;$i++){
			$vv=substr($v,0,strlen($v)-$len*($max-$i-1));

			$n[]=$vv;
			$v=substr($v,strlen($v)-$len*($max-$i-1));
		}
		return implode(",",$n);
	}
	//is utf8
	public static function is_utf8($string) {
		return preg_match( '%^(?:
		 [\x09\x0A\x0D\x20-\x7E]			# ASCII
	   | [\xC2-\xDF][\x80-\xBF]			# non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]		# excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]		# excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}	# planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}		  # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}	# plane 16
   )*$%xs', $string );
	}
	//安全encode
	public static function safeEncoding( $string, $outEncoding = 'UTF-8' ) {
		$string = Util::safeEncoding3( $string );

		if (Util::is_utf8( $string ))  return $string;

		$encoding = "UTF-8";
		$encoding = mb_detect_encoding( $string, array( 'ASCII', 'GB2312', 'CP936', 'BIG5' ) );

		if ( strtoupper( $encoding ) == strtoupper( $outEncoding ) ) {
			return $string;
		} else {
			return mb_convert_encoding( $string, $outEncoding, $encoding );
		}
	}
	//安全encode
	public static function safeEncoding3($string, $outEncoding = 'UTF-8') {
		$encoding = "UTF-8";
		for ($i = 0; $i < strlen( $string ); $i++) {
			if ( ord( $string{$i} ) < 128 ) continue;

			if ( ( ord( $string{$i} ) & 224 ) == 224 ) {
				//第一个字节判断通过
				$char = $string{++$i};
				if ( ( ord( $char ) & 128 ) == 128 ) {
					//第二个字节判断通过
					$char = $string{++$i};
					if ( ( ord( $char ) & 128 ) == 128 ) {
						$encoding = "UTF-8";
						break;
					}
				}
			}
			if ( ( ord( $string{$i} ) & 192 ) == 192 ) {
				//第一个字节判断通过
				$char = $string{++$i};
				if ( ( ord( $char ) & 128 ) == 128 ) {
					// 第二个字节判断通过
					$encoding = "GB2312";
					break;
				}
			}
		}

		if ( strtoupper( $encoding ) == strtoupper( $outEncoding ) )
			return $string;
		else
			return mb_convert_encoding( $string, $outEncoding, $encoding );
		return iconv( $encoding, $outEncoding, $string );
	}

	//取浏览器语言
	public static function determineLang() {
		$lang = substr(@$_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4); //只取前4位，这样只判断最优先的语言。如果取前5位，可能出现en,zh的情况，影响判断。
		//if (preg_match("/zh-c/i", $lang)) return "zh_CN"; else
		if (preg_match("/zh/i", $lang)) return "zh_TW";
		//else if (preg_match("/en/i", $lang)) return "en_US";
		//else if (preg_match("/fr/i", $lang)) return "fr_FR";
		//else if (preg_match("/de/i", $lang)) return "de_DE";
		//else if (preg_match("/jp/i", $lang)) return "ja_JP";
		//else if (preg_match("/ko/i", $lang)) return "ko_KR";
		//else if (preg_match("/es/i", $lang)) return "es_ES";
		//else if (preg_match("/sv/i", $lang)) return "sv_SE";
		//else return $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		else return 'en_US';
	}
	//urlencode
	public static function encode_url_substr_UTF8($str,$lenlimit){
		$mblen=mb_strlen($str,"UTF8");
		$realencodelen=0;
		$outstr='';
		for($i=0;$i<$mblen;$i++){
			$char=mb_substr($str,$i,1,'UTF8');
			if(strlen($char)>1){
				$addlen=strlen($char)*3;
			}else{
				$addlen=1;
			}
			if($realencodelen+$addlen<$lenlimit){
				$outstr.=$char;
				$realencodelen+=$addlen;
			}
		}
		return $outstr;
	}
	//转意数据
	public static function saddslashes($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = Util::saddslashes($val);
			}
		} else {
			$string = addslashes($string);
		}
		return $string;
	}
	//日志
	public static function WriteLog($key, $content) {
		$ymd = date("Ymd");
		$date = date('Y-m-d H:i:s');
		$file = DOCROOT."application/logs/{$ymd}_{$key}.html";
		$ip = @Util::getIP();

		if(!is_string($content)) {
			$content = json_encode($content);
		}
		$uid = isset($GLOBALS['user_id']) ? $GLOBALS['user_id'] : 0;
		$params = array('POST' => $_POST, 'GET' => $_GET, 'php://input' => file_get_contents('php://input', 'r'));
		$params = json_encode($params);
		$content = "date:\t\t{$date}<br>\nreferer:\t".@$_SERVER['HTTP_REFERER']."<br>\nrequest:\t".@$_SERVER['REQUEST_URI']."<br>\nparams:\t\t{$params}<br>\nip:\t\t{$ip}({$uid})<br>\ncontent:\t{$content}\n\n";

		error_log($content, 3, $file);
	}
	//写简单日志
	public static function SmallLog($key, $content) {
		$ymd = date("Ymd");
		$date = date('Y-m-d H:i:s');
		$file = DOCROOT."application/logs/{$ymd}_{$key}.html";
		$ip = @Util::getIP();

		if(!is_string($content)) {
			$content = json_encode($content);
		}
		$uid = isset($GLOBALS['user_id']) ? $GLOBALS['user_id'] : 0;
		$content = "date:{$date}\tip:{$ip}({$uid})\tcontent:{$content}<br>\n";

		error_log($content, 3, $file);
	}
	//验证昵称
	public static function check_nickname($username,$isNick=false) {
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = strlen($username);
		if($len > 20 || $len < 1 || preg_match("/^c:\\con\\con|[%,\|\*\"\<\>\&\`\']|$guestexp/is", $username)) {
			return FALSE;
		} else if( preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/',$username)){
			return TRUE;
		}

		return FALSE;
	}
	//验证用户名
	public static function check_username($username) {
		$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = strlen($username);
		if($len > 20 || $len < 3 || preg_match("/\s+|^c:\\con\\con|[%,\|\*\"\s\<\>\&]|$guestexp/is", $username)||!preg_match("/^[0-9@A-Z_a-z]*$/", $username)) {
			return FALSE;
		}else if(strstr(' ', $username)){
			return FALSE;
		} else {
			return TRUE;
		}
	}
	//验证email
	public static function check_emailformat($email) {
		return strlen($email) > 6 && preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email);
	}
	//curl
	public static function curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		$reponse = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new Exception(curl_error($ch),0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}
	//生成密码
	public static function password($user_password, $user_salt) {
		return md5(md5($user_password).strval($user_salt));
	}
	//生成sessionKey
	public static function makePkey($uid, $timestamp, $locale, $mobile = 1) {
		$pkey = array ('u' => $uid, 't' => $timestamp, 'l' => $locale,'m' => $mobile);
		$pkey = Util::up_encode ( $pkey );
		return $pkey;
	}
	//加密
	public static function up_encode($str) {
		if (is_array ( $str ) || is_object ( $str )) $str = json_encode ( $str );
		$cr = new Crypt3Des ( Crypt3DesKey, Crypt3DesIV );
		$str = $cr->encrypt ( $str );
		$str = str_replace ( '+', '-', $str );
		$str = str_replace ( '/', '.', $str );
		$str = str_replace ( '=', '!', $str );
		return $str;
	}
	//解密
	public static function up_decode($str) {
		if (!$str) return $str;
		$str = urldecode ( $str );
		$str = trim ( $str );
		$cr = new Crypt3Des ( Crypt3DesKey, Crypt3DesIV );
		$str = str_replace ( '-', '+', $str );
		$str = str_replace ( '.', '/', $str );
		$str = str_replace ( '!', '=', $str );
		$str = $cr->decrypt ( $str );
		$couldbeA = json_decode ( $str, true );
		if (is_array ( $couldbeA )) return $couldbeA;
		return false;
	}
	public static function getIP() {
		if (getenv ( 'HTTP_CLIENT_IP' ) && strcasecmp ( getenv ( 'HTTP_CLIENT_IP' ), 'unknown' )) {
			$onlineip = getenv ( 'HTTP_CLIENT_IP' );
		} elseif (getenv ( 'HTTP_X_FORWARDED_FOR' ) && strcasecmp ( getenv ( 'HTTP_X_FORWARDED_FOR' ), 'unknown' )) {
			$onlineip = getenv ( 'HTTP_X_FORWARDED_FOR' );
		} elseif (getenv ( 'REMOTE_ADDR' ) && strcasecmp ( getenv ( 'REMOTE_ADDR' ), 'unknown' )) {
			$onlineip = getenv ( 'REMOTE_ADDR' );
		} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown' )) {
			$onlineip = $_SERVER ['REMOTE_ADDR'];
		}
		preg_match ( "/[\d\.]{7,15}/", $onlineip, $onlineipmatches );
		return $onlineipmatches [0] ? $onlineipmatches [0] : false;
	}
}
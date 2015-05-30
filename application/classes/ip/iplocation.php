<?php
//*
//文件头 [第一条索引的偏移量 (4byte)] + [最后一条索引的偏移地址 (4byte)]	 8字节
//记录区 [结束ip (4byte)] + [地区1] + [地区2]								4字节+不定长
//索引区 [开始ip (4byte)] + [指向记录区的偏移地址 (3byte)]				   7字节
//注意:使用之前请去网上下載纯真IP数据库,并改名为 "CoralWry.dat" 放到当前目录下即可.
//by 查询吧 www.query8.com
//*

class IpLocation {
var $fp;
var $firstip;  //第一条ip索引的偏移地址
var $lastip;   //最后一条ip索引的偏移地址
var $totalip;  //总ip数

public static $ipl = null;

public static function getIPAddress() {
	if (!IpLocation::$ipl) IpLocation::$ipl = new IpLocation(DOCROOT.'application/classes/ip/QQWry.Dat');
	$ip = IpLocation::$ipl->getIP();
	if (!$ip) return  false;
	$address = IpLocation::$ipl->getaddress($ip);
	return mb_convert_encoding( $address ["area1"] . $address ["area2"], 'utf-8', 'GB2312' );
}
public static function getIPAddress2() {
	if (!IpLocation::$ipl) IpLocation::$ipl = new IpLocation(DOCROOT.'application/classes/ip/QQWry.Dat');
	$ip = IpLocation::$ipl->getIP();
	if (!$ip) return  false;
	return IpLocation::$ipl->getaddress($ip);
}
public static function getAddressByIP($ip) {
	if (!$ip) return "未知";
	if (!IpLocation::$ipl) IpLocation::$ipl = new IpLocation(DOCROOT.'application/classes/ip/QQWry.Dat');
	$address = IpLocation::$ipl->getaddress($ip);
	return mb_convert_encoding( $address ["area1"] . $address ["area2"], 'utf-8', 'GB2312' );
}

//*
//构造函数,初始化一些变量
//$datfile 的值为纯真IP数据库的名子,可自行修改.
//*
function ipLocation(){
  $datfile = DOCROOT.'application/classes/ip/QQWry.Dat';
  $this->fp=fopen($datfile,'rb')or die("QQWry.Dat不存在，请去网上下載纯真IP数据库, 'QQWry.dat' 放到当前目录下");   //二制方式打开
  $this->firstip = $this->get4b(); //第一条ip索引的绝对偏移地址
  $this->lastip = $this->get4b();  //最后一条ip索引的绝对偏移地址
  $this->totalip =($this->lastip - $this->firstip)/7 ; //ip總數 索引区是定长的7个字节,在此要除以7,
  register_shutdown_function(array($this,"closefp"));  //为了兼容php5以下版本,本类没有用析构函数,自动关闭ip库.
}
//*
//关闭ip库
//*
function closefp(){
fclose($this->fp);
}
//*
//读取4个字节并将解压成long的长模式
//*
function get4b(){
  $str=unpack("V",fread($this->fp,4));
  return $str[1];
}
//*
//读取重定向了的偏移地址
//*
function getoffset(){
  $str=unpack("V",fread($this->fp,3).chr(0));
  return $str[1];
}
//*
//读取ip的详细地址信息
//*
function getstr(){
	$str = '';
  $split=fread($this->fp,1);
  while (ord($split)!=0) {
	$str .=$split;
	$split=fread($this->fp,1);
  }
  return $str;
}
//*
//将ip通过ip2long转成ipv4的互联网地址,再将他压缩成big-endian字节序
//用来和索引区内的ip地址做比较
//*
function iptoint($ip){
  return pack("N",intval(ip2long($ip)));
}
//*
//获取客户端ip地址
//注意:如果你想要把ip记录到服务器上,请在写库时先检查一下ip的数据是否安全.
//*

//*
//获取地址信息
//*
function readaddress(){
  $now_offset=ftell($this->fp); //得到当前的指针位址
  $flag=$this->getflag();
  switch (ord($flag)){
		 case 0:
			 $address="";
		 break;
		 case 1:
		 case 2:
			 fseek($this->fp,$this->getoffset());
			 $address=$this->getstr();
		 break;
		 default:
			 fseek($this->fp,$now_offset);
			 $address=$this->getstr();
		 break;
  }
  return $address;
}
//*
//获取标志1或2
//用来确定地址是否重定向了.
//*
function getflag(){
  return fread($this->fp,1);
}
//*
//用二分查找法在索引区内搜索ip
//*
function searchip($ip){
  $ip=gethostbyname($ip);	 //将域名转成ip
  $ip_offset["ip"]=$ip;
  $ip=$this->iptoint($ip);	//将ip转换成长整型
  $firstip=0;				 //搜索的上边界
  $lastip=$this->totalip;	 //搜索的下边界
  $ipoffset=$this->lastip;	//初始化为最后一条ip地址的偏移地址
  while ($firstip <= $lastip){
	$i=floor(($firstip + $lastip) / 2);		  //计算近似中间记录 floor函数记算给定浮点数小的最大整数,说白了就是四舍五也舍
	fseek($this->fp,$this->firstip + $i * 7);	//定位指针到中间记录
	$startip=strrev(fread($this->fp,4));		 //读取当前索引区内的开始ip地址,并将其little-endian的字节序转换成big-endian的字节序
	if ($ip < $startip) {
	   $lastip=$i - 1;
	}
	else {
	   fseek($this->fp,$this->getoffset());
	   $endip=strrev(fread($this->fp,4));
	   if ($ip > $endip){
		  $firstip=$i + 1;
	   }
	   else {
		  $ip_offset["offset"]=$this->firstip + $i * 7;
		  break;
	   }
	}
  }
  return $ip_offset;
}
//*
//获取ip地址详细信息
//*
function getaddress($ip){
  $ip_offset=$this->searchip($ip);  //获取ip 在索引区内的绝对编移地址
  $ipoffset=$ip_offset["offset"];
  $address["ip"]=$ip_offset["ip"];
  fseek($this->fp,$ipoffset);	  //定位到索引区
  $address["startip"]=long2ip($this->get4b()); //索引区内的开始ip 地址
  $address_offset=$this->getoffset();			//获取索引区内ip在ip记录区内的偏移地址
  fseek($this->fp,$address_offset);			//定位到记录区内
  $address["endip"]=long2ip($this->get4b());   //记录区内的结束ip 地址
  $flag=$this->getflag();					  //读取标志字节
  switch (ord($flag)) {
		 case 1:  //地区1地区2都重定向
		 $address_offset=$this->getoffset();   //读取重定向地址
		 fseek($this->fp,$address_offset);	 //定位指针到重定向的地址
		 $flag=$this->getflag();			   //读取标志字节
		 switch (ord($flag)) {
				case 2:  //地区1又一次重定向,
				fseek($this->fp,$this->getoffset());
				$address["area1"]=$this->getstr();
				fseek($this->fp,$address_offset+4);	  //跳4个字节
				$address["area2"]=$this->readaddress();  //地区2有可能重定向,有可能没有
				break;
				default: //地区1,地区2都没有重定向
				fseek($this->fp,$address_offset);		//定位指针到重定向的地址
				$address["area1"]=$this->getstr();
				$address["area2"]=$this->readaddress();
				break;
		 }
		 break;
		 case 2: //地区1重定向 地区2没有重定向
		 $address1_offset=$this->getoffset();   //读取重定向地址
		 fseek($this->fp,$address1_offset);
		 $address["area1"]=$this->getstr();
		 fseek($this->fp,$address_offset+8);
		 $address["area2"]=$this->readaddress();
		 break;
		 default: //地区1地区2都没有重定向
		 fseek($this->fp,$address_offset+4);
		 $address["area1"]=$this->getstr();
		 $address["area2"]=$this->readaddress();
		 break;
  }
  //*过滤一些无用数据
  if (strpos($address["area1"],"CZ88.NET")!=false){
	  $address["area1"]="未知";
  }
  if (strpos($address["area2"],"CZ88.NET")!=false){
	  $address["area2"]=" ";
  }
  return $address;
 }

}
//*ipLocation class end
class IpLocationNew {
	/**
	 * @var resource 指针
	 */
	private $fp;

	/**
	 * 第一条IP记录的偏移地址
	 * @var int
	 */
	private $firstip;

	/**
	 * 最后一条IP记录的偏移地址
	 * @var int
	 */
	private $lastip;

	/**
	 * IP记录的总条数（不包含版本信息记录）
	 * @var int
	 */
	private $totalip;

	/**
	 * 构造函数，打开 QQWry.Dat 文件并初始化类中的信息
	 * @param string $filename
	 * @return IpLocation
	 */
	public function __construct($filename = "qqwry.dat") {
		$this->fp = 0;
		if (($this->fp = @fopen($filename, 'rb')) !== false) {
			$this->firstip = $this->getlong();
			$this->lastip = $this->getlong();
			$this->totalip = ($this->lastip - $this->firstip) / 7;
		}
	}

	/**
	 * 返回读取的长整型数
	 * @access private
	 * @return int
	 */
	public function getlong() {
		//将读取的little-endian编码的4个字节转化为长整型数
		$result = unpack('Vlong', fread($this->fp, 4));
		return $result['long'];
	}

	/**
	 * 返回读取的3个字节的长整型数
	 *
	 * @access private
	 * @return int
	 */
	public function getlong3() {
		//将读取的little-endian编码的3个字节转化为长整型数
		$result = unpack('Vlong', fread($this->fp, 3).chr(0));
		return $result['long'];
	}

	/**
	 * 返回压缩后可进行比较的IP地址
	 *
	 * @access private
	 * @param string $ip
	 * @return string
	 */
	public function packip($ip) {
		// 将IP地址转化为长整型数，如果在PHP5中，IP地址错误，则返回False，
		// 这时intval将Flase转化为整数-1，之后压缩成big-endian编码的字符串
		return pack('N', intval(ip2long($ip)));
	}

	/**
	 * 返回读取的字符串
	 *
	 * @access private
	 * @param string $data
	 * @return string
	 */
	public function getstring($data = "") {
		$char = fread($this->fp, 1);
		while (ord($char) > 0) { // 字符串按照C格式保存，以\0结束
			$data .= $char; // 将读取的字符连接到给定字符串之后
			$char = fread($this->fp, 1);
		}
		return mb_convert_encoding($data, 'utf-8', 'gb2312');
	}

	/**
	 * 返回地区信息
	 *
	 * @access private
	 * @return string
	 */
	public function getarea() {
		$byte = fread($this->fp, 1); // 标志字节
		switch (ord($byte)) {
			case 0: // 没有區域信息
				$area = "";
				break;
			case 1:
			case 2: // 标志字节为1或2，表示區域信息被重定向
				fseek($this->fp, $this->getlong3());
				$area = $this->getstring();
				break;
			default: // 否则，表示區域信息没有被重定向
				$area = $this->getstring($byte);
				break;
		}
		return $area;
	}

	/**
	 * 根据所给 IP 地址或域名返回所在地区信息
	 * @access public
	 * @param string $ip
	 * @return array
	 */
	function getlocation($ip) {
		if (!$this->fp) return null; // 如果数据文件没有被正确打开，则直接返回空
		$location['ip'] = gethostbyname($ip); // 将输入的域名转化为IP地址
		$ip = $this->packip($location['ip']); // 将输入的IP地址转化为可比较的IP地址
		// 不合法的IP地址会被转化为255.255.255.255
		// 对分搜索
		$l = 0; // 搜索的下边界
		$u = $this->totalip; // 搜索的上边界
		$findip = $this->lastip; // 如果没有找到就返回最后一条IP记录（QQWry.Dat的版本信息）
		while ($l <= $u) { // 当上边界小于下边界时，查找失败
			$i = floor(($l + $u) / 2); // 计算近似中间记录
			fseek($this->fp, $this->firstip + $i * 7);
			$beginip = strrev(fread($this->fp, 4)); // 获取中间记录的开始IP地址
			// strrev函数在这里的作用是将little-endian的压缩IP地址转化为big-endian的格式
			// 以便用于比较，后面相同。
			if ($ip < $beginip) { // 用户的IP小于中间记录的开始IP地址时
				$u = $i - 1; // 将搜索的上边界修改为中间记录减一
			}else{
				fseek($this->fp, $this->getlong3());
				$endip = strrev(fread($this->fp, 4)); // 获取中间记录的结束IP地址
				if ($ip > $endip) { // 用户的IP大于中间记录的结束IP地址时
					$l = $i + 1; // 将搜索的下边界修改为中间记录加一
				}else{ // 用户的IP在中间记录的IP范围内时
					$findip = $this->firstip + $i * 7;
					break; // 则表示找到结果，退出循环
				}
			}
		}

		//获取查找到的IP地理位置信息
		fseek($this->fp, $findip);
		$location['beginip'] = long2ip($this->getlong()); // 用户IP所在范围的开始地址
		$offset = $this->getlong3();
		fseek($this->fp, $offset);
		$location['endip'] = long2ip($this->getlong()); // 用户IP所在范围的结束地址
		$byte = fread($this->fp, 1); // 标志字节

		switch (ord($byte)) {
			case 1: // 标志字节为1，表示国家和區域信息都被同时重定向
				$countryOffset = $this->getlong3(); // 重定向地址
				fseek($this->fp, $countryOffset);
				$byte = fread($this->fp, 1); // 标志字节
				switch (ord($byte)) {
					case 2: // 标志字节为2，表示国家信息又被重定向
						fseek($this->fp, $this->getlong3());
						$location['country'] = $this->getstring();
						fseek($this->fp, $countryOffset + 4);
						$location['area'] = $this->getarea();
						break;
					default: // 否则，表示国家信息没有被重定向
						$location['country'] = $this->getstring($byte);
						$location['area'] = $this->getarea();
						break;
				}
				break;
			case 2: // 标志字节为2，表示国家信息被重定向
				fseek($this->fp, $this->getlong3());
				$location['country'] = $this->getstring();
				fseek($this->fp, $offset + 8);
				$location['area'] = $this->getarea();
				break;
			default: // 否则，表示国家信息没有被重定向
				$location['country'] = $this->getstring($byte);
				$location['area'] = $this->getarea();
				break;
		}
		if ($location['country'] == " CZ88.NET") { // CZ88.NET表示没有有效信息
			$location['country'] = "未知";
		}
		if ($location['area'] == " CZ88.NET") {
			$location['area'] = "";
		}
		return $location;
	}


	/**
	 * 析构函数，用于在页面执行结束后自动关闭打开的文件。
	 *
	 */
	function __desctruct() {
		if ($this->fp) {
			fclose($this->fp);
		}
		$this->fp = 0;
	}
}
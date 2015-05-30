<?php defined('SYSPATH') OR die('No direct script access.');

class Controller extends Kohana_Controller {
	public function __construct($request, $response) {
		parent::__construct($request, $response);
	}
	//jsonp
	public function jsonp($data, $callback = 'callback') {
		@header('Content-Type: application/json');
		@header("Expires:-1");
		@header("Cache-Control:no-cache");
		@header("Pragma:no-cache");
		if (isset($_REQUEST[$callback])) {
			header("Access-Control-Allow-Origin:*");
			echo $_REQUEST[$callback].'('.json_encode($data).')';
		} else echo json_encode($data);
		exit;
	}
	//json
	public function json($data){
		@header('Content-Type: application/json');
		@header("Expires:-1");
		@header("Cache-Control:no-cache");
		@header("Pragma:no-cache");
		echo json_encode($data);
		exit;
	}
	//语言配置
	public function getLang($lang = 'zh_TW'){
		return Kohana::$config->load(strtolower($this->getLocale($lang)));
	}
	//过滤语言
	public function getLocale($lang) {
		$shortLangEN = array('en', 'US', 'UK', "en_US", 'en_UK', 'en_GB');
		$shortLangTW = array('zh', 'TW', 'HK', "zh_TW", 'zh_HK', "jp", 'ja_JP', 'ko_KR', "th", "TH", 'th_TH');
		$shortLangCN = array('zh_CN', 'CN');
		if (in_array($lang, $shortLangEN)) {
			$locale = "en_US";
		} else if (in_array($lang, $shortLangTW)) {
			$locale = "zh_TW";
		} else if (in_array($lang, $shortLangCN)) {
			$locale = "zh_CN";
		} else {
			$locale = "en_US";
		}
		return $locale;
	}
	public function checkAllowIP() {
		$ip = Util::getIP();
		$ips = array("127.0.0.1");
		if(!in_array($ip, $ips) && substr($ip, 0,7) != "192.168") exit;
	}
	public function req($key, $val = null) {
		if (isset($_REQUEST[$key])) return @$_REQUEST[$key];
		return $val;
	}
}
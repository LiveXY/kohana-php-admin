<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class AppController extends BaseController {
	public function __construct($request, $response) {
		parent::__construct ( $request, $response );
	}
	//初始化数据
	public function initApp(&$obj) {
		$sessionKey = $obj->req('sessionKey');

		$obj->income = Util::up_decode($sessionKey);
		if ($obj->income && $obj->income['u'] > 0) {
			$obj->uid = $obj->income['u'];
			$obj->user = CacheManager::getUser($obj->uid);
			if (!isset($obj->income['l'])) $obj->income['l'] = $obj->user->locale;
			$obj->locale = $obj->getLocale($obj->income['l']);
			$obj->lang = $obj->getLang($obj->locale);
			$obj->mobile = $obj->income['m'] == 1 ? true : false;
			$obj->login($obj->uid);
		} else if ($GLOBALS['user_id'] > 0) {
			$obj->uid = $GLOBALS['user_id'];
			$obj->user = CacheManager::getUser($obj->uid);
			if (!$obj->user) {
				$GLOBALS['user_id'] = 0;
				$obj->uid = 0;
				$obj->locale = $obj->getLocale(Util::determineLang());
				$obj->lang = $obj->getLang($obj->locale);
			} else {
				$obj->locale = $obj->getLocale($obj->user->locale);
				$obj->lang = $obj->getLang($obj->locale);
			}
		} else {
			$obj->locale = $obj->getLocale(Util::determineLang());
			$obj->lang = $obj->getLang($obj->locale);
		}
		if ($obj->uid > 0) {
			$uri = addslashes(@$_SERVER['REQUEST_URI']);
			if (Util::filterOnlineUrl($uri)) {
				$update_time = 0; $key = 'last_time_'.$obj->uid;
				$time = CacheManager::cache_get($key, 0);
				if ($time > 0) { $update_time = TIMESTAMP - $time; }
				if ($update_time < 0) $update_time = 0;
				Model::factory('App')->updateUserOnline($obj->uid, $uri, Util::getIP(), $update_time);
				CacheManager::cache_set($key, TIMESTAMP);
			}
		}
	}
	//检查是否登陆 未登陆时跳转到登陆界面
	public function checkLogin(){ if ($GLOBALS['user_id'] < 1) $this->redirect('home/login'); }
	//检查是否登陆
	public function isLogin(){ return !($GLOBALS['user_id'] < 1); }
	//添加text/html utf-8 content-type类型
	public function addUTF8Header() {
		header( "Content-type: text/html; charset=utf-8" );
	}
}

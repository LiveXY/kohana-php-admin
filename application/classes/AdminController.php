<?php defined('SYSPATH') OR die('No direct script access.');

class AdminController extends BaseController {
	public function __construct($request, $response) {
		parent::__construct($request, $response);
		//$this->checkAllowIP();

		$role_id = $this->isAdmin();
		if ($role_id < 1) return $this->redirect('admin/login/index');

		if ($GLOBALS['user_id'] > 0) {
			$user = CacheManager::getUser();
			$uri = addslashes(@$_SERVER['REQUEST_URI']);
			if (Util::filterOnlineUrl($uri)) {
				$update_time = 0; $key = 'last_time_'.$GLOBALS['user_id'];
				$time = CacheManager::cache_get($key, 0);
				if ($time > 0) { $update_time = TIMESTAMP - $time; }
				if ($update_time < 0) $update_time = 0;
				Model::factory('App')->updateAdminOnline($GLOBALS['user_id'], $uri, Util::getIP(), $update_time, addslashes(json_encode(@$_REQUEST)));
				CacheManager::cache_set($key, TIMESTAMP);
			}
		}
	}
	//View
	public function mainView($file, $data) {
		$role_id = $this->isAdmin();
		if ($role_id < 1) return $this->redirect('admin/login/index');

		$functions = Model::factory('Sys')->userFunction($role_id);
		$menus = array();

		$market = false;

		foreach ($functions as $func) {
			$menus[$func->app_id]['title'] = $func->app_name;
			$menus[$func->app_id]['code'] = $func->app_ename;
			$menus[$func->app_id]['img'] = $func->app_img;

			if ($func->func_ename == 'PersonalSetting') $market = true;

			$menus[$func->app_id]['children'][$func->func_id]['title'] = $func->func_name;
			$menus[$func->app_id]['children'][$func->func_id]['code'] = $func->func_ename;
			$menus[$func->app_id]['children'][$func->func_id]['url'] = $func->func_url;
			$menus[$func->app_id]['children'][$func->func_id]['img'] = $func->func_img;
		}

		$data['admin_menus'] = $menus;
		$data['main_content'] = View::factory($file, $data);
		$data['market'] = $market;

		echo View::factory('admin/main', $data);
	}
	public function iframeView($file, $data) {
		$data['admin_user'] = CacheManager::getAdmin();
		$data['main_content'] = View::factory($file, $data);
		echo View::factory('admin/iframe', $data);
	}
	public function paramError(){
	   return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");
	}
	//出错消息
	public  function msg($message) {
		$DATA = array();
		$DATA['message'] = $message;
		$DATA['title'] = "系統提示";
		$this->iframeView('msg/msg', $DATA);
		exit;
	}
	//验证功能权限
	public function checkFunction($func, $op = 'view'){
		if (!$this->isFunction($func, $op)) return $this->msg("无操作權限! ");
	}
}
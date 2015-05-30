<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * controller基类
 * @author Lee
 */
class BaseController extends Controller {

	public function __construct($request, $response) {
		parent::__construct($request, $response);

		define('TIMESTAMP', time());
		define('DATETIME', date('Y-m-d H:i:s', TIMESTAMP));
		//登陆初始化
		$GLOBALS['user_id'] = Cookie::get('user_id', 0);
	}
	//View
	protected function ViewBefore() {
		echo View::factory('header');
	}
	protected function ViewAfter() {
		echo View::factory('footer');
	}
	public function view($file, $data = array()) {
		$this->ViewBefore();
		echo View::factory($file, $data);
		$this->ViewAfter();
	}
	public function viewNew($file, $data = array()) {
		echo View::factory($file, $data);
	}
	//登陆
	public function login($user_id) {
		$user = CacheManager::getUser($user_id);
		if($user) {
			$GLOBALS['user_id'] = $user_id;
			Cookie::set('user_id', $user_id);
		}
		return $user;
	}
	//退出
	public function logout() {
		$GLOBALS['user_id'] = 0;
		Cookie::delete('user_id');
	}
	//是否管理员
	public function isAdmin(){
		if (!$GLOBALS['user_id'] || $GLOBALS['user_id'] < 1) return false;
		$admin = CacheManager::getAdmin($GLOBALS['user_id']);
		if (!$admin) return false;
		return intval($admin['role_id']);
	}
	//是否有功能的操作
	public function isFunction($func, $op = 'view') {
		$func = $this->funcOp($func);
		if (!$func) return false;
		return $func[$op];
	}
	//是否有功能
	public function funcOp($func){
		if (!$GLOBALS['user_id'] || $GLOBALS['user_id'] < 1) return false;
		$admin = CacheManager::getAdmin($GLOBALS['user_id']);
		if (!$admin || !isset($admin[$func])) return false;
		return $admin[$func];
	}
	//用户登陆验证
	public function getpassport($username, $password) {
		if(!Util::check_username($username)) return -4;
		$key = 'logins_'.$username;
		$logins = CacheManager::cache_get($key, 0);
		if ($logins > 10) return -5;
		CacheManager::cache_set($key, $logins + 1);
		return $this->check_login($username, $password);
	}
	public function check_login($username, $password) {
		$user = Model::factory('Sys')->getUserByName($username);
		if (!$user) return -1;
		if(empty($user->username)) {
			return -1;
		} elseif($user->password != Util::password($password, $user->user_salt)) {
			return -2;
		}
		CacheManager::cache_del('logins_'.$username);
		return $user;
	}
}
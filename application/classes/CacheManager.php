<?php defined('SYSPATH') OR die('No direct script access.');

class CacheManager {
	private static $year = 31536000; //一年
	private static $day = 86400; //一天
	private static $config = 'memcache'; //memcache redis
	private static $prefix = 'Test_'; //前缀
	private static $cacheInstance = null; //实例

	//清理在线用户数据
	public static function removeOnlineUsers() {
		$list = Model::factory("App")->onlineUserIDs();
		foreach ($list as $info) {
			self::removeUser($info->uid);
			self::removeAdmin($info->uid);
		}
	}
	//管理员
	public static function getAdmin($user_id = null){
		if ($user_id == null) $user_id = $GLOBALS['user_id'];
		if ($user_id == null) return null;
		return self::cache(array(Model::factory('Sys'), 'getAdmin'), array($user_id), self::$day);
	}
	public static function removeAdmin($user_id = null){
		if ($user_id == null) $user_id = $GLOBALS['user_id'];
		if ($user_id == null) return null;
		self::cache_delete(array(Model::factory('Sys'), 'getAdmin'), array($user_id));
	}
	//用户信息
	public static function getUser($user_id = null){
		if ($user_id == null) $user_id = $GLOBALS['user_id'];
		if ($user_id == null) return null;
		return self::cache(array(Model::factory('Sys'), 'getUserByID'), array($user_id), self::$day);
	}
	public static function removeUser($user_id = null){
		if ($user_id == null) $user_id = $GLOBALS['user_id'];
		if ($user_id == null) return null;
		self::cache_delete(array(Model::factory('Sys'), 'getUserByID'), array($user_id));
		self::removeAdmin($user_id);
	}

	//锁
	public static function cacheLockStart($key, $seconds = 10) {
		$key = self::$prefix.$key;
		$value = self::CacheInstanceGet($key, null);
		if ($value) return false;
		self::CacheInstanceSet($key, 1, $seconds);
		return true;
	}
	public static function cacheLockEnd($key) {
		$key = self::$prefix.$key;
		self::CacheInstanceDelete($key);
	}
	//当天CACHE
	public static function dayStatus($uid, $key, $update = true){
		$key = self::$prefix.'day_status_'.$key.'_'.$uid;
		$value = intval(self::CacheInstanceGet($key, 0));
		if ($update && $value == 0) self::CacheInstanceSet($key, 1, intval(strtotime(date("Y-m-d",strtotime("+1 day")))-time()));
		return $value == 0;
	}
	//CACHE
	private static function CacheInstance() {
		if (UseCache == 'memcache' && !self::$cacheInstance) self::$cacheInstance = Cache::instance(self::$config);
		if (UseCache == 'redis' && !self::$cacheInstance) self::$cacheInstance = Koredis::factory();
		return self::$cacheInstance;
	}
	private static function CacheInstanceGet($key, $def = null){
		if (UseCache == 'memcache') return self::CacheInstance()->get($key, $def);
		if (UseCache == 'redis') {
			if (!self::CacheInstance()->exists($key)) return $def;
			return unserialize(self::CacheInstance()->get($key));
		}
	}
	private static function CacheInstanceSet($key, $value, $lifetime = null){
		if (UseCache == 'memcache') return self::CacheInstance()->set($key, $value, $lifetime);
		if (UseCache == 'redis') return self::CacheInstance()->setex($key, $lifetime, serialize($value));
	}
	private static function CacheInstanceDelete($key){
		return self::CacheInstance()->delete($key);
	}
	public static function cache($func, $params = array(), $seconds = 3600){
		$key = self::cache_param($func, $params);
		$value = self::CacheInstanceGet($key);
		if (!$value) {
			$value = call_user_func_array($func, $params);
			//var_dump($value);
			self::CacheInstanceSet($key, $value, $seconds);
		}
		return $value;
	}
	public static function cache_json($func, $params = array(), $seconds = 3600){
		$key = self::cache_param($func, $params);
		$value = self::CacheInstanceGet($key);
		if (!$value) {
			$value = json_encode(call_user_func_array($func, $params));
			//var_dump($value);
			self::CacheInstanceSet($key, $value, $seconds);
		}
		return json_decode($value);
	}
	public static function cache_delete($func, $params = array()){
		self::CacheInstanceDelete(self::cache_param($func, $params));
	}
	public static function cache_get($key, $value = NULL) {
		$key = self::$prefix.$key;
		return self::CacheInstanceGet($key, $value);
	}
	public static function cache_set($key, $value, $lifetime = 3600) {
		$key = self::$prefix.$key;
		return self::CacheInstanceSet($key, $value, $lifetime);
	}
	public static function cache_del($key) {
		$key = self::$prefix.$key;
		return self::CacheInstanceDelete($key);
	}
	private static function cache_param($func, $params){
		$key = self::$prefix;
		if (is_string($func)) {
			$key .= $func;
		} else if (is_array($func)) {
			foreach ($func as $info) {
				if (is_object($info)) $key .= get_class($info);
				if (is_string($info)) $key .= '_'.$info;
			}
		}
		return $key.'_'.implode('_', array_values($params));
	}
}
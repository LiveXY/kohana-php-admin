<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Setting extends AdminController {
	public function action_index(){}
	//缓存管理
	public function action_cache_list() {
		$this->checkFunction("CacheManage");

		View::set_global('title', '管理');
		echo $this->iframeView('admin/setting/cache_list', array());
	}
}
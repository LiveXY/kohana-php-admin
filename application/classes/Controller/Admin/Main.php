<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Main extends AdminController {

	public function action_index() {
		$DATA = array();
		View::set_global('title', '后台管理系统');
		$this->mainView('admin/main/index', $DATA);
	}

	public function action_home() {
		$DATA = array();
		View::set_global('title', '管理主页');
		$this->iframeView('admin/main/index', $DATA);
	}

}

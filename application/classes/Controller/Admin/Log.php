<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Log extends AdminController {

	//操作日志
	public function action_tree(){
		$this->checkFunction("LogManage");
		$DATA = array();
		View::set_global('title', '日志');
		echo $this->iframeView('admin/log/tree', $DATA);
	}
	//用户信息
	public function action_me(){
		$DATA = array();
		View::set_global('title', '日志');
		$uid = $this->request->query('uid');
		$DATA['uid'] = $uid;
		$DATA['islog'] = $this->isFunction("LogManage");
		echo $this->iframeView('admin/log/me', $DATA);
	}
	//管理员操作日志
	public function action_admin_op_log(){
		$this->checkFunction("LogManage");

		$page = intval($this->request->query('page'));
		if ($page < 1) $page = 1;

		$key = $this->request->query('key');
		$begin = $this->request->query('begin');
		$end = $this->request->query('end');
		if (!$begin) $begin = date('Y-m-d');
		if (!$end) $end = date('Y-m-d');

		$DATA = array();
		$DATA['list'] 		= Model::factory('Log')->admin_op_log($begin, $end, $key, $page, 25);
		$DATA['totals'] 	= Model::factory('Log')->admin_op_log_count($begin, $end, $key);
		$DATA['key'] 		= $key;
		$DATA['begin'] 		= $begin;
		$DATA['end'] 		= $end;
		$DATA['page'] 		= $page;

		View::set_global('title', '管理员操作日志');
		echo $this->iframeView('admin/log/admin_op_log', $DATA);
	}
	//网站登陆日志
	public function action_user_login_log(){
		$this->checkFunction("LogManage");

		$page = intval($this->request->query('page'));
		if ($page < 1) $page = 1;

		$key = $this->request->query('key');
		$begin = $this->request->query('begin');
		$end = $this->request->query('end');
		if (!$begin) $begin = date('Y-m-d');
		if ($end == '请选择终止日期' or $end=='') $end = date('Y-m-d');

		$DATA = array();
		$DATA['list'] 		= Model::factory('Log')->user_login_log($begin, $end, $key, $page, 25);
		$DATA['totals'] 	= Model::factory('Log')->user_login_log_count($begin, $end, $key);
		$DATA['key'] 		= $key;
		$DATA['begin'] 		= $begin;
		$DATA['end'] 		= $end;
		$DATA['page'] 		= $page;

		View::set_global('title', '用户登录日志');
		echo $this->iframeView('admin/log/user_login_log', $DATA);
	}
	//网站在线用户
	public function action_user_online(){
		$this->checkFunction("LogManage");

		$page = intval($this->request->query('page'));
		if ($page < 1) $page = 1;

		$key = $this->request->query('key');
		$begin = $this->request->query('begin');
		$end = $this->request->query('end');
		if (!$begin) $begin = date('Y-m-d');
		if (!$end) $end = date('Y-m-d');

		$DATA = array();
		$DATA['list'] 		= Model::factory('Log')->user_online($begin, $end, $key, $page, 25);
		$DATA['totals'] 	= Model::factory('Log')->user_online_count($begin, $end, $key);
		$DATA['key'] 		= $key;
		$DATA['begin'] 		= $begin;
		$DATA['end'] 		= $end;
		$DATA['page'] 		= $page;

		View::set_global('title', '在线用户');
		echo $this->iframeView('admin/log/user_online', $DATA);
	}

}
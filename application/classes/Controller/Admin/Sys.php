<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Sys extends AdminController {
	public function action_index() { }
	public function action_user_control__(){
		$ids = $this->request->query('ids');
		$json = array();
		if (!is_array($ids)) return $this->jsonp($json);
		$ids = array_unique($ids);
		foreach ($ids as $id) {
			if (!is_numeric($id)) continue;
			$json[$id] = CacheManager::getUser($id);
			if (!$json[$id]) continue;

			unset($json[$id]->password, $json[$id]->user_salt);
			$json[$id]->regip = $json[$id]->regprovince.$json[$id]->regcity.$json[$id]->regarea;
			$json[$id]->reg_date = date('Y-m-d H:i:s', $json[$id]->reg_date);
			$json[$id]->loginip = '';
			$json[$id]->login_date = $json[$id]->login_date == 0 ? '' : date('Y-m-d H:i:s', $json[$id]->login_date);
			$json[$id]->avatar = Util::getAvatar($json[$id]->avatar);
		}
		return $this->jsonp($json);
	}
	public function action_func_list() {
		$this->checkFunction("FunctionManage");
		$DATA = array();

		$functions = Model::factory('Sys')->functions();
		$funcs = array();

		foreach ($functions as $func) {
			$funcs[$func->app_id]['app_id'] 		= $func->app_id;
			$funcs[$func->app_id]['app_name'] 		= $func->app_name;
			$funcs[$func->app_id]['app_ename'] 		= $func->app_ename;
			$funcs[$func->app_id]['app_img'] 		= $func->app_img;
			$funcs[$func->app_id]['app_order'] 		= $func->app_order;
			$funcs[$func->app_id]['app_status'] 	= $func->app_status;

			$funcs[$func->app_id]['children'][$func->func_id]['func_id'] 		= $func->func_id;
			$funcs[$func->app_id]['children'][$func->func_id]['func_name'] 		= $func->func_name;
			$funcs[$func->app_id]['children'][$func->func_id]['func_ename'] 	= $func->func_ename;
			$funcs[$func->app_id]['children'][$func->func_id]['func_url'] 		= $func->func_url;
			$funcs[$func->app_id]['children'][$func->func_id]['func_img'] 		= $func->func_img;
			$funcs[$func->app_id]['children'][$func->func_id]['func_order'] 	= $func->func_order;
			$funcs[$func->app_id]['children'][$func->func_id]['func_status'] 	= $func->func_status;
		}

		$DATA['funcs'] = $funcs;

		$napps = Model::factory('Sys')->napps();
		$DATA['napps'] = $napps;
		$DATA['user_right'] = $this->funcOp('FunctionManage');

		View::set_global('title', '功能管理');
		echo $this->iframeView('admin/sys/func_list', $DATA);
	}
	public function action_func_op(){
		$func_id = intval($this->request->query('func_id'));
		$app_id = intval($this->request->query('app_id'));

		$DATA = array();
		$DATA['func_id'] 	= $func_id;
		$DATA['app_id'] 	= $app_id;
		$DATA['func'] 		= Model::factory('Sys')->func($func_id);
		$DATA['apps'] 		= Model::factory('Sys')->apps();
		$DATA['user_right'] = $this->funcOp('FunctionManage');
		$DATA['images'] 	= Util::getPathFiles(DOCROOT."client/images/ico/");

		View::set_global('title', $func_id>0 ? '修改功能' : '添加功能');
		echo $this->iframeView('admin/sys/func_op', $DATA);
	}
	public function action_func_post(){
		$func_id 	= intval($this->request->query('func_id'));

		$txtName 	= $this->request->post('txtName');
		$txtEName 	= $this->request->post('txtEName');
		$txtImg 	= $this->request->post('txtImg');
		$txtUrl 	= $this->request->post('txtUrl');
		$txtOrder 	= intval($this->request->post('txtOrder'));
		$cboStatus 	= intval($this->request->post('cboStatus'));
		$cboApp 	= intval($this->request->post('cboApp'));

		$data = array(
			'app_id'		=> $cboApp,
			'func_name'		=> $txtName,
			'func_ename'	=> $txtEName,
			'func_img'		=> $txtImg,
			'func_order'	=> $txtOrder,
			'func_url'		=> $txtUrl,
			'status'		=> $cboStatus,
		);

		if ($func_id<1) { //添加
			$this->checkFunction('FunctionManage', "add");
			$result = Model::factory('Sys')->insertFunc($data);
		} else { //修改
			$this->checkFunction('FunctionManage', "edit");
			$result = Model::factory('Sys')->updateFunc($func_id, $data);
		}
		CacheManager::removeAdmin();
		return $this->redirect('admin/sys/func_list');
	}
	public function action_func_delete(){
		$this->checkFunction('FunctionManage', "delete");
		$func_id = intval($this->request->query('func_id'));
		if ($func_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");
		Model::factory('Sys')->deleteFunc($func_id);
		CacheManager::removeAdmin();
		return $this->redirect('admin/sys/func_list');
	}

	public function action_app_op(){
		$app_id = intval($this->request->query('app_id'));

		$DATA = array();
		$DATA['app_id'] 	= $app_id;
		$DATA['app'] 		= Model::factory('Sys')->app($app_id);
		$DATA['user_right'] = $this->funcOp('FunctionManage');
		$DATA['images'] 	= Util::getPathFiles(DOCROOT."client/images/ico/");

		View::set_global('title', $app_id>0 ? '修改应用' : '添加应用');
		echo $this->iframeView('admin/sys/app_op', $DATA);
	}
	public function action_app_post(){
		$app_id = intval($this->request->query('app_id'));

		$txtName 		= $this->request->post('txtName');
		$txtEName 		= $this->request->post('txtEName');
		$txtImg 		= $this->request->post('txtImg');
		$txtOrder 		= intval($this->request->post('txtOrder'));
		$cboStatus 		= intval($this->request->post('cboStatus'));

		$data = array(
			'app_name'	=> $txtName,
			'app_ename'	=> $txtEName,
			'app_img'	=> $txtImg,
			'app_order'	=> $txtOrder,
			'status'	=> $cboStatus,
		);

		if ($app_id<1) { //添加
			$this->checkFunction('FunctionManage', "add");
			$result = Model::factory('Sys')->insertApp($data);
		} else { //修改
			$this->checkFunction('FunctionManage', "edit");
			$result = Model::factory('Sys')->updateApp($app_id, $data);
		}
		return $this->redirect('admin/sys/func_list');
	}
	public function action_app_delete(){
		$this->checkFunction('FunctionManage', "delete");
		$app_id = intval($this->request->query('app_id'));
		if ($app_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");
		Model::factory('Sys')->deleteApp($app_id);
		Model::factory('Sys')->deleteFuncs($app_id);
		return $this->redirect('admin/sys/func_list');
	}

	public function action_role_list() {
		$this->checkFunction("RoleManage");
		$DATA = array();
		$DATA['roles'] 		= Model::factory('Sys')->rolesAll();
		$DATA['user_right'] = $this->funcOp('RoleManage');

		View::set_global('title', '角色管理');
		echo $this->iframeView('admin/sys/role_list', $DATA);
	}
	public function action_role_op(){
		$role_id = intval($this->request->query('role_id'));

		$DATA = array();
		$DATA['role_id'] 	= $role_id;
		$DATA['role'] 		= Model::factory('Sys')->role($role_id);
		$DATA['user_right'] = $this->funcOp('RoleManage');

		$funcs = array();
		$apps = Model::factory('Sys')->apps2();
		foreach ($apps as $app) $funcs[$app->app_id] = Model::factory('Sys')->funcs($app->app_id, $role_id);

		$DATA['apps'] = $apps;
		$DATA['funcs'] = $funcs;
		View::set_global('title', $role_id>0 ? '修改角色' : '添加角色');
		echo $this->iframeView('admin/sys/role_op', $DATA);
	}
	public function action_role_post(){
		$role_id = intval($this->request->query('role_id'));

		$txtName 			= $this->request->post('txtName');
		$txtEName 			= $this->request->post('txtEName');
		$txtFuncsID 		= str_replace(" ", "",  $this->request->post('txtFuncsID'));
		$cboStatus 			= intval($this->request->post('cboStatus'));
		$txtFuncs 			= $this->request->post('txtFuncsName');

		$data = array(
			'role_name'		=> $txtName,
			'role_ename'	=> $txtEName,
			'role_funcnames'=> $txtFuncs,
			'role_funcids'	=> $txtFuncsID,
			'status'		=> $cboStatus,
		);
		if ($role_id<1) { //添加
			$this->checkFunction('RoleManage', "add");
			$result = Model::factory('Sys')->insertRole($data);
			if ($result) $role_id = $result[0];
		} else { //修改
			$this->checkFunction('RoleManage', "edit");
			$result = Model::factory('Sys')->updateRole($role_id, $data);
		}
		if ($result && strlen($txtFuncsID)>1) {
			Model::factory('Sys')->deleteRoleFuncs($role_id);
			$funcs = explode(';', $txtFuncsID);
			foreach ($funcs as $func_name){
				$funcid_list = explode('-', $func_name);
				if (count($funcid_list)!=5) continue;

				$func_id = intval($funcid_list[4]);
				if ($func_id<1) continue;
				$data = array(
					'role_id'	=> $role_id,
					'func_id'	=> $func_id,
					'func_op'	=> $func_name,
				);
				Model::factory('Sys')->insertRoleFuncs($data);
			}
		}
		CacheManager::removeAdmin();
		return $this->redirect('admin/sys/role_list');
	}
	public function action_role_delete(){
		$this->checkFunction('RoleManage', "delete");

		$role_id = intval($this->request->query('role_id'));
		if ($role_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");
		Model::factory('Sys')->deleteRole($role_id);
		CacheManager::removeAdmin();
		return $this->redirect('admin/sys/role_list');
	}

	public function action_admin_list() {
		$this->checkFunction("AdminManage");
		$DATA = array();
		$DATA['admins'] 	= Model::factory('Sys')->admins();
		$DATA['user_right'] = $this->funcOp('AdminManage');

		View::set_global('title', '管理员管理');
		echo $this->iframeView('admin/sys/admin_list', $DATA);
	}
	public function action_admin_op(){
		$user_id = intval($this->request->query('user_id'));

		$DATA = array();
		$DATA['user_id'] 	= $user_id;
		$DATA['admin'] 		= Model::factory('Sys')->admin($user_id);
		$DATA['roles'] 		= Model::factory('Sys')->roles();
		$DATA['user_right'] = $this->funcOp('AdminManage');

		View::set_global('title', $user_id>0 ? '修改管理员' : '添加管理员');
		echo $this->iframeView('admin/sys/admin_op', $DATA);
	}
	public function action_admin_post(){
		$user_id = intval($this->request->query('user_id'));
		$txtGame = $this->request->post('txtGame');

		$role_id = intval($this->request->post('cboRole'));
		if ($role_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");

		if ($user_id<1) { //添加
			$this->checkFunction('AdminManage', "add");
			$user_id = intval($this->request->post('txtUserID'));
			if ($user_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");

			$data = array(
				'user_id'	=> $user_id,
				'role_id'	=> $role_id,
			);
			$result = Model::factory('Sys')->insertAdmin($data);
		} else { //修改
			$this->checkFunction('AdminManage', "edit");
			$data = array(
					'role_id'=>$role_id,
			);
			$result = Model::factory('Sys')->updateAdmin($user_id, $data);
		}
		CacheManager::removeAdmin($user_id);
		return $this->redirect('admin/sys/admin_list');
	}
	public function action_admin_delete(){
		$this->checkFunction('AdminManage', "delete");
		$user_id = intval($this->request->query('user_id'));
		if ($user_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");
		$result = Model::factory('Sys')->deleteAdmin($user_id);
		return $this->redirect('admin/sys/admin_list');
	}

	public function action_user_list() {
		$this->checkFunction("UserManage");

		$page = intval($this->request->query('page'));
		if ($page < 1) $page = 1;

		$key = $this->request->query('key');
		$begin = $this->request->query('begin');
		$end = $this->request->query('end');

		$oc = intval($this->request->query('oc'));
		if ($oc < 1) $oc = 1;
		$os = $this->request->query('os');
		if (strlen($os) == 0 || $os == 'desc') $os = 'desc'; else $os = 'asc';

		$DATA = array();
		$DATA['users'] 		= Model::factory('Sys')->users($begin, $end, $key, $oc, $os, $page, 25);
		$DATA['totals'] 	= Model::factory('Sys')->users_count($begin, $end, $key);
		$DATA['user_right'] = $this->funcOp('UserManage');
		$DATA['key'] 		= $key;
		$DATA['oc'] 		= $oc;
		$DATA['os'] 		= $os;
		$DATA['begin'] 		= $begin;
		$DATA['end'] 		= $end;
		$DATA['page'] 		= $page;

		View::set_global('title', '用户管理');
		echo $this->iframeView('admin/sys/user_list', $DATA);
	}
	public function action_user_op(){
		$user_id = intval($this->request->query('uid'));

		$DATA = array();
		$DATA['uid'] 		= $user_id;
		$DATA['user'] 		= $user_id == 0 ? false : CacheManager::getUser($user_id);
		$DATA['user_right'] = $this->funcOp('UserManage');

		View::set_global('title', $user_id>0 ? '修改用户' : '添加用户');
		echo $this->iframeView('admin/sys/user_op', $DATA);
	}
	public function action_user_post(){
		$user_id = intval($this->request->query('uid'));

		$user = $this->request->post('user');
		$password = $this->request->post('password');

		if ($user_id<1) { //添加
			$this->checkFunction('UserManage', "add");
			if (!empty($password)) {
				$salt = substr(uniqid(rand()), -6);
				$user['deviceid'] = $user['username'];
				$user['user_salt'] = $salt;
				$user['password'] = Util::password($password, $salt);
			}
			$user['login_ip'] = Util::getIP();
			$user['login_times'] = TIMESTAMP;
			$result = Model::factory('Sys')->insertUser($user);
		} else { //修改
			$this->checkFunction('UserManage', "edit");
			$u = CacheManager::getUser($user_id);

			if (!empty($password)) {
				$user['password'] = Util::password($password, $u->user_salt);
			}
			Model::factory('Sys')->updateUser($user_id, $user);
		}
		CacheManager::removeUser($user_id);

		$DATA = array();
		$DATA['uid'] 		= $user_id;
		$DATA['user'] 		= CacheManager::getUser($user_id);
		$DATA['user_right'] = $this->funcOp('UserManage');

		View::set_global('title', $user_id>0 ? '修改用户' : '添加用户');
		return $this->iframeView('admin/sys/user_op', $DATA);
	}
	public function action_user_delete(){
		$this->checkFunction('UserManage', "delete");
		$user_id = intval($this->request->query('uid'));
		if ($user_id < 1) return $this->msg("参数错误！ <a href='javascript:void()' onclick='history.go(-1);'>返回</a>");
		Model::factory('Sys')->deleteUser($user_id);
		Model::factory('Sys')->deleteAdmin($user_id);
		CacheManager::removeUser($user_id);
		return $this->redirect('admin/sys/user_list');
	}
	public function action_admin_check(){
		$DATA = array();
		$DATA["user_id"] 	= 0; //-1 用户不存在 －2已是管理员
		$DATA["user_email"] = "";
		$DATA["user_nick"] 	= "";

		$key = $this->request->query('key');
		if (strlen($key) == 0) return $this->json($DATA);

		$user = Model::factory('Sys')->userCheck($key);
		if (!$user) { $DATA["user_id"] = -1; return $this->json($DATA); }

		$role_id = Model::factory('Sys')->adminRole($user->uid);
		if ($role_id > 0) { $DATA["user_id"] = -2; return $this->json($DATA); }

		$DATA["user_id"] 	= $user->uid;
		$DATA["user_email"] = $user->email;
		$DATA["user_nick"] 	= $user->nickname;
		return $this->json($DATA);
	}
	public function action_user_check(){
		$DATA = array();

		$user_name = $this->request->query('key');
		$count = Model::factory('Sys')->check_user_name($user_name);

		$DATA["msg"] = $count > 0 ? "* 电子邮件已经被注册！" : "";
		return $this->json($DATA);
	}
	public function action_admin_checkFunc(){
		$role_id = intval($this->request->query('role_id'));
		if ($role_id < 1) return $this->json("[]");

		$functions = Model::factory('Sys')->userFunction($role_id);
		$menus = array();

		foreach ($functions as $func) {
			$menus[$func->app_id]['app_id'] 	= $func->app_id;
			$menus[$func->app_id]['app_name'] 	= $func->app_name;
			$menus[$func->app_id]['app_ename'] 	= $func->app_ename;
			$menus[$func->app_id]['app_img'] 	= $func->app_img;

			$op = $func->func_op;
			$ops = explode('-', $op);
			$name = $func->func_name;
			if ($op && count($ops)==5) {
				unset($ops[4]);
				$ops[0] = (strlen($ops[0])>0?"√":"×")."浏览";
				$ops[1] = (strlen($ops[1])>0?"√":"×")."添加";
				$ops[2] = (strlen($ops[2])>0?"√":"×")."修改";
				$ops[3] = (strlen($ops[3])>0?"√":"×")."删除";
				$op = implode('  ', $ops);
				$name = str_pad($name, 20);
				$name = $name.$op ;
			}

			$menus[$func->app_id]['children'][$func->func_id]['func_id'] 	= $func->func_id;
			$menus[$func->app_id]['children'][$func->func_id]['func_name'] 	= $name;
			$menus[$func->app_id]['children'][$func->func_id]['func_ename'] = $func->func_ename;
			$menus[$func->app_id]['children'][$func->func_id]['func_url'] 	= $func->func_url;
			$menus[$func->app_id]['children'][$func->func_id]['func_img'] 	= $func->func_img;
		}

		$json = "";
		$tree = array();
		foreach ($menus as $app) {
			$tree['id'] 	= $app['app_id'];
			$tree['name'] 	= $app['app_name'];
			$tree['pId'] 	= "";
			$tree['open'] 	= true;
			$tree['icon'] 	= RESOURCE.'images/ico/'.$app['app_img'];
			$json=$json.json_encode($tree).',';

			foreach ($app['children'] as $func) {
				$tree['id'] 	= 'func-'.$func['func_id'];
				$tree['name'] 	= $func['func_name'];
				$tree['pId'] 	= $app['app_id'];
				$tree['icon'] 	= RESOURCE.'images/ico/'.$func['func_img'];
				$json=$json.json_encode($tree).',';
			}
		}
		if (strlen($json)>0) $json = rtrim($json, ",");
		return $this->json("[".$json."]");
	}
}
<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Login extends BaseController {

	public function action_index() {
		if ($this->isAdmin()) return $this->redirect(ROOTURL.'/admin/main/index');
		echo View::factory('admin/login/index');
	}

	public function action_post(){
		$username 			= $this->request->post('username');
		$password 			= $this->request->post('password');
		$captcha 			= $this->request->post('captcha');

		if($username AND $password) {
			//if(Captcha::valid($captcha)) {
				$passport = $this->getpassport($username, $password);
				if (!is_object($passport)) {
					if ($passport == -1) {
						$DATA['msg'] = "对不起，你使用的用户名尚未注册！";
					} elseif ($passport == -2) {
						$DATA['msg'] = "对不起，密码错误，请重新输入。";
					} elseif ($passport == -3) {
						$DATA['msg'] = "账号已经冻结客服";
					} elseif ($passport == -5) {
						$DATA['msg'] = "登陆次数过多，请联系客服";
					} else {
						$DATA['msg'] = '登录失败，请确认用户名或者邮箱正确！';
					}
				} else {
					$user_id = $passport->uid;
					$user = $this->login($user_id);
					Model::factory('Sys')->updateLoginData($user, 3);
					return $this->redirect(ROOTURL.'/admin/main/index');
				}
			//} else {
			//	$DATA['msg'] = '登陆失败，验证码错误！';
			//}
		} else $DATA['msg'] = '用户名或者Email错误！';

		echo View::factory('admin/login/index', $DATA);
	}

	public function action_logout() {
		$this->logout();
		return $this->redirect(ROOTURL.'/admin/login/index');
	}

}
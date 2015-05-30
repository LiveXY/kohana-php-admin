<?php defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class Controller_App extends AppController {
	public $lang = null, $income = null, $locale = null, $user = null, $uid = 0, $platform = 'appstore', $mobile = false;

	public function __construct($request, $response) {
		parent::__construct ( $request, $response );
		$this->initApp($this);
	}

	public function paramError() { $this->jsonp(array("ret" => 4, "msg" => $this->lang['invalid_param'])); }
	public function loginError() { $this->jsonp(array("ret" => 1, "msg" => $this->lang['reset_login'], 'relogin' => 1)); }
	public function checkData() { if (!$this->income || $this->uid < 1 || !$this->user || !$this->lang) $this->loginError(); }
	public function errorKey($key, $code = 1) { $this->jsonp(array("ret" => $code, "msg" => $this->lang[$key])); }
	public function errorMsg($msg, $code = 1) { $this->jsonp(array("ret" => $code, "msg" => $msg)); }

}

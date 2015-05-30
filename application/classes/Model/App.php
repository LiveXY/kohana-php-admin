<?php defined('SYSPATH') OR die('No direct script access.');

class Model_App extends Model {

	public function __construct() {
		parent::__construct();
	}

	//在线用户编号列表
	public function onlineUserIDs(){
		$sql = "select user_id as uid from user_online";
		return $this->db->query(Database::SELECT, $sql, true);
	}

	public function crontab_report_day($day){
		$sql = "call crontab_report_day({$day})";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count;
	}

	//更新管理员在线状态
	public function updateAdminOnline($user_id, $url, $ip, $time, $request){
		$data = array('user_id'=>$user_id,'user_url'=>$url,'url_ip'=>$ip,'last_time'=>TIMESTAMP);
		$sql = 'update user_online set '.$this->set($data).' where user_id='.$user_id;
		$result = $this->db->query(Database::UPDATE, $sql, true);
		if (!$result) {
			$sql = 'insert into user_online('.$this->fields($data).') values('.$this->values($data).')';
			$this->db->query(Database::INSERT, $sql, true);
		}

		$sql = "update sys_user set online = 1, online_times = online_times + $time where uid=$user_id";
		$this->db->query(Database::UPDATE, $sql, true);

		$data = array('uid'=>$user_id,'url'=>$url,'request'=>$request,'ip'=>$ip,'ltime'=>TIMESTAMP);
		$sql = 'insert into log_admin_op('.$this->fields($data).') values('.$this->values($data).')';
		$this->db->query(Database::INSERT, $sql, true);
		return 1;
	}
	public function updateUserOnline($user_id, $url, $ip, $time){
		$data = array('user_id'=>$user_id,'user_url'=>$url,'url_ip'=>$ip,'last_time'=>TIMESTAMP);
		$sql = 'update user_online set '.$this->set($data).' where user_id='.$user_id;
		$result = $this->db->query(Database::UPDATE, $sql, true);
		if (!$result) {
			$sql = 'insert into user_online('.$this->fields($data).') values('.$this->values($data).')';
			$this->db->query(Database::INSERT, $sql, true);
		}

		$sql = "update sys_user set online = 1, online_times = online_times + $time where uid=$user_id";
		$this->db->query(Database::UPDATE, $sql, true);
		return 1;
	}

	public function logUserLogin($data) {
		$sql = 'insert into log_user_login('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}


}

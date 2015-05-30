<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Log extends Model {

	//管理员操作日志
	public function admin_op_log($begin, $end, $key, $page = 1, $pageSize = 25){
		$sql = "select * from log_admin_op where 1=1 ";
		if (strlen($begin) > 0) $sql .= " and ltime>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and ltime<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and uid=".$key;
			else $sql .= " and (url like '%".$key."%' or request like '%".$key."%')";
		}
		$sql .= " order by id desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;

		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function admin_op_log_count($begin, $end, $key) {
		$sql = "select count(id) count from log_admin_op where 1=1 ";
		if (strlen($begin) > 0) $sql .= " and ltime>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and ltime<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and uid=".$key;
			else $sql .= " and (url like '%".$key."%' or request like '%".$key."%')";
		}

		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count;
	}
	//网站登陆日志
	public function user_login_log($begin, $end, $key, $page = 1, $pageSize = 25){
		$sql = "select * from log_user_login where 1=1 ";
		if (strlen($begin) > 0) $sql .= " and ldate>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and ldate<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and uid=".$key;
		}
		$sql .= " order by id desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;

		$sql .= " LIMIT {$limit} OFFSET {$offset}";

		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function user_login_log_count($begin, $end, $key) {
		$sql = "select count(id) count from log_user_login where 1=1 ";
		if (strlen($begin) > 0) $sql .= " and ldate>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and ldate<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and uid=".$key;
		}

		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count;
	}
	//在线用户
	public function user_online($begin, $end, $key, $page = 1, $pageSize = 25){
		$sql = "select * from user_online where 1=1 ";
		if (strlen($begin) > 0) $sql .= " and last_time>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and last_time<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and user_id=".$key;
			else $sql .= " and (user_url like '%".$key."%' or url_ip like '%".$key."%')";
		}
		$sql .= " order by user_id desc";

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;

		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function user_online_count($begin, $end, $key) {
		$sql = "select count(user_id) count from user_online where 1=1 ";
		if (strlen($begin) > 0) $sql .= " and last_time>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and last_time<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and user_id=".$key;
			else $sql .= " and (user_url like '%".$key."%' or url_ip like '%".$key."%')";
		}

		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count;
	}
}

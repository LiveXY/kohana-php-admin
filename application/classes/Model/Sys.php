<?php defined('SYSPATH') OR die('No direct script access.');

class Model_Sys extends Model {
	public function __construct() {
		parent::__construct();
	}
	public function register($username, $usernick = '', $utype = 'web', $locale = 'zh_CN') {
		$ip = Util::getIP();
		$city = false;
		try {
			$city = Util::getIp2Address($ip);
		} catch (Exception $e) { }
		$member = array(
			'username' => $username,
			'nickname' => $usernick ? $usernick : '',
			'utype' => $utype ? $utype : '',
			'reg_ip' => $ip,
			'reg_date' => TIMESTAMP,
			'locale' => $locale,
		);
		if ($city) {
			$member['regprovince'] = $city['region'];
			$member['regcity'] = $city['city'];
			$member['regarea'] = $city['county'];
		}
		$result = $this->insertUser($member);
		return $result ? $result[0] : false;
	}
	public function getAdmin($uid){
		$role_id = $this->adminRole($uid);
		if ($role_id == 0) return false;
		$data = array('role_id'=> $role_id);
		$user_ops = $this->userFuncOps($uid);

		foreach ($user_ops as $ops) {
			$ename = $ops->func_ename;
			$op = $ops->func_op;

			$data[$ename]['view'] = is_bool(strrpos($op, "view")) ? false : true;
			$data[$ename]['add'] = is_bool(strrpos($op, "add")) ? false : true;
			$data[$ename]['edit'] = is_bool(strrpos($op, "edit")) ? false : true;
			$data[$ename]['delete'] = is_bool(strrpos($op, "delete")) ? false : true;
		}

		return $data;
	}
	public function adminRole($user_id) {
		$sql = "select role_id from sys_admin_user where status=1 and user_id={$user_id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current()->role_id : 0;
	}
	public function userFunction($role_id){
		$sql = "select a.func_id, b.app_id, func_name, func_ename, func_url, func_img, app_ename, app_name, app_img, func_op
				from sys_role_function as a inner join sys_app_function as b on a.func_id=b.func_id and b.status=1
				inner join sys_app as c on c.app_id = b.app_id  and c.status=1
				where role_id={$role_id} order by app_order asc, func_order asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function admins(){
		$sql = "select a.user_id, nickname, email, role_name, reg_date, role_funcnames
				from sys_admin_user as a inner join sys_role as b on a.role_id = b.role_id and a.status=1
				inner join sys_user as c on a.user_id=c.uid
				order by b.role_id asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function users_count($begin,$end,$key){
		$sql = "SELECT count(uid) count FROM sys_user where 1=1";
		if (strlen($begin) > 0) $sql .= " and reg_date>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and reg_date<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and uid=".$key;
			else if (Util::validate_email($key)) $sql .= " and email='{$key}'";
			else $sql .= " and (username like '%".$key."%' or email like '%".$key."%' or nickname like '%".$key."%' or cardNO like '%".$key."%')";
		}

		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count;
	}
	public function users($begin, $end, $key, $oc = 1, $os = 'desc', $page = 1, $pageSize = 25){
		$sql = "SELECT * FROM sys_user WHERE 1=1 ";
		if (strlen($begin) > 0) $sql .= " and reg_date>=".strtotime($begin);
		if (strlen($end) > 0) $sql .= " and reg_date<=".strtotime('+1 day', strtotime($end));

		if (strlen($key)>0) {
			if (is_numeric($key)) $sql .= " and uid=".$key;
			else if (Util::validate_email($key)) $sql .= " and email='{$key}'";
			else $sql .= " and (username like '%".$key."%' or email like '%".$key."%' or nickname like '%".$key."%' or cardNO like '%".$key."%')";
		}

		if ($oc == 2) $sql .= " order by login_date ".$os;
		else $sql .= " order by uid ".$os;

		$offset = ($page - 1) * $pageSize;
		$limit = $pageSize;

		$sql .= " LIMIT {$limit} OFFSET {$offset}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function getUserByID($user_id){
		$sql = 'select * from sys_user where uid='.$user_id;
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function getUserByName($username){
		$sql = "select * from sys_user where username='{$username}' limit 1";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function getUserByEmail($email){
		$sql = "select * from sys_user where email='{$email}' limit 1";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function getUserByMobile($mobile){
		$sql = "select * from sys_user where tel='{$mobile}' limit 1";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function check_user_name($user_name) {
		$sql = "select COUNT(user_id) AS total from sys_user where username='{$user_name}'";
		return $this->db->query(Database::SELECT, $sql, true)->current()->total;
	}
	public function admin($user_id) {
		$sql = "select a.user_id, email, nickname, a.role_id
				from sys_admin_user as a inner join sys_role as b on a.role_id = b.role_id and a.status=1
				inner join sys_user as c on a.user_id=c.uid
				where a.user_id={$user_id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function insertAdmin($data) {
		$sql = 'insert into sys_admin_user('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateAdmin($user_id, $data) {
		$sql = 'update sys_admin_user set '.$this->set($data).' where user_id='.$user_id;
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteAdmin($user_id) {
		$sql = "delete from sys_admin_user where user_id={$user_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function insertUser($data) {
		$sql = 'insert into sys_user('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateUser($user_id, $data) {
		$sql = 'update sys_user set '.$this->set($data).' where uid='.$user_id;
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteUser($user_id) {
		$sql = "delete from sys_user where uid={$user_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function userCheck($key) {
		$user_id = intval($key);
		 if ($user_id >0 && $user_id.''==$key) {
			$sql = 'select uid, nickname, email
				from sys_user
				where uid='.$key;
		} else {
			$sql = "select uid, nickname, email
				from sys_user
				where username='".$key."'";
		}
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function existAdmin($id) {
		$sql = "select count(user_id) count from sys_admin_user where user_id={$id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count != 0;
	}
	public function checkFunction($user_id, $func, $op = "view"){
		$sql = "select count(a.user_id) count
				from sys_admin_user as a inner join sys_role as b on a.role_id=b.role_id and b.status=1 and a.status=1
				inner join sys_role_function as c on c.role_id=a.role_id
				inner join sys_app_function as d on d.func_id=c.func_id and d.status=1 and func_ename='{$func}'
				where user_id={$user_id} and LOCATE('{$op}', func_op)>0";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->current()->count > 0;
	}
	public function userFuncOps($user_id){
		$sql = "select func_ename,func_op
			from sys_admin_user as a inner join sys_role as b on a.role_id=b.role_id and b.status=1 and a.status=1
			inner join sys_role_function as c on c.role_id=a.role_id
			inner join sys_app_function as d on d.func_id=c.func_id and d.status=1
			where user_id={$user_id}";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function funcOp($user_id, $func){
		$sql = "select func_op
				from sys_admin_user as a inner join sys_role as b on a.role_id=b.role_id and b.status=1 and a.status=1
				inner join sys_role_function as c on c.role_id=a.role_id
				inner join sys_app_function as d on d.func_id=c.func_id and d.status=1 and func_ename='{$func}'
				where user_id={$user_id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current()->func_op : false;
	}

	public function roles(){
		$sql = "select role_id,role_name,role_ename,role_funcnames,role_funcids from sys_role where status=1";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function rolesAll(){
		$sql = "select role_id,role_name,role_ename,status,role_funcnames,role_funcids from sys_role";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function role($role_id) {
		$sql = "select * from sys_role where role_id={$role_id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function insertRole($data) {
		$sql = 'insert into sys_role('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateRole($role_id, $data) {
		$sql = 'update sys_role set '.$this->set($data).' where role_id='.$role_id;
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteRole($role_id) {
		$sql = "delete from sys_role where role_id={$role_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function deleteRoleFuncs($role_id) {
		$sql = "delete from sys_role_function where role_id={$role_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function insertRoleFuncs($data) {
		$sql = 'insert into sys_role_function('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}

	public function functions(){
		$sql = "select func_id, a.app_id, func_name, func_ename, func_url, func_img, func_order, a.status as func_status,
				app_name, app_ename, app_img, app_order, b.status as app_status
				from sys_app_function as a inner join sys_app as b on a.app_id=b.app_id
				order by app_order asc, func_order asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function napps(){
		$sql = "select app_id, app_ename, app_name, app_img, app_order, status
				from sys_app as a
				where not exists(select app_id from sys_app_function where app_id=a.app_id)
				order by app_order asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function apps2(){
		$sql = "select app_id, app_ename, app_name, app_img, app_order, status
				from sys_app as a
				where exists(select app_id from sys_app_function where app_id=a.app_id)
				order by app_order asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function apps(){
		$sql = "select app_id, app_ename, app_name, app_img, app_order, status from sys_app order by app_order asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function funcs($app_id, $rid){
		$sql = "select a.func_id, func_ename, func_name, func_img, func_order, status, role_id
				from sys_app_function as a left join sys_role_function as b on a.func_id=b.func_id and role_id={$rid}
				where app_id={$app_id}
				order by func_order asc";
		return $this->db->query(Database::SELECT, $sql, true);
	}
	public function func($func_id) {
		$sql = "select * from sys_app_function where func_id={$func_id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function app($app_id) {
		$sql = "select * from sys_app where app_id={$app_id}";
		$query = $this->db->query(Database::SELECT, $sql, true);
		return $query->valid() ? $query->current() : false;
	}
	public function insertApp($data) {
		$sql = 'insert into sys_app('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateApp($app_id, $data) {
		$sql = 'update sys_app set '.$this->set($data).' where app_id='.$app_id;
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteApp($app_id) {
		$sql = "delete from sys_app where app_id={$app_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function insertFunc($data) {
		$sql = 'insert into sys_app_function('.$this->fields($data).') values('.$this->values($data).')';
		return $this->db->query(Database::INSERT, $sql, true);
	}
	public function updateFunc($func_id, $data) {
		$sql = 'update sys_app_function set '.$this->set($data).' where func_id='.$func_id;
		return $this->db->query(Database::UPDATE, $sql, true);
	}
	public function deleteFunc($func_id) {
		$sql = "delete from sys_app_function where func_id={$func_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
	public function deleteFuncs($app_id) {
		$sql = "delete from sys_app_function where app_id={$app_id}";
		return $this->db->query(Database::DELETE, $sql, true);
	}
}

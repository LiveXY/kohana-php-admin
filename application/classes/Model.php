<?php defined('SYSPATH') OR die('No direct script access.');

class Model extends Kohana_Model {

	public $db;

	public function __construct() {
		$this->db = Database::instance(UseDB);
		if (UseDB == 'mysql') $this->db->set_charset('utf8');
	}

	//开始事务
	public function begin() {
		$this->db->begin();
	}
	//提交事务
	public function commit() {
		$this->db->commit();
	}
	//回滚事务
	public function rollback() {
		$this->db->rollback();
	}
	//值转换＋
	public function values($data) {
		$sql = array();
		foreach($data as $key=>$value) {
			$sql[] = $this->_toString($value);
		}
		return implode(',', $sql);
	}
	private function _toString($value) {
		if($value === null) {
			return 'NULL';
		} elseif ($value === true) {
			return '1';
		} elseif ($value === false) {
			return '0';
		} else {
			return $this->db->escape($value);
		}
	}
	//字段＋
	public function fields($data) {
		return '`'.implode('`,`', array_keys($data)).'`';
	}
	//修改＋
	public function set($data) {
		$sql = array();
		foreach($data as $key=>$value) {
			$sql[] = sprintf('`%s`=%s', $key, $this->_toString($value));
		}
		return implode(',', $sql);
	}
	//批量insert＋
	public function combinateSql($data){
		$insertValue = '';
		foreach ($data as $value){
			if($insertValue)	$insertValue .= ",(".$this->values($value).")";
			else				$insertValue .= "values(".$this->values($value).")";
		}
		$result['insertKey'] 	= $data[0];
		$result['insertValue'] 	= $insertValue;
		return $result;
	}
}
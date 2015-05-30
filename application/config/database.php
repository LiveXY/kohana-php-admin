<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'mysql' => array(
		'type'       => 'PDO',
		'connection' => array(
			'dsn'        => 'mysql:host=127.0.0.1;dbname=test',
			'username'   => 'root',
			'password'   => '',
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => FALSE,
	),
	'sqlite' => array(
		'type'       => 'PDO_SQLite',
		'connection' => array(
			'dsn'        => 'sqlite:/Users/hcxiong/happi_pro/git_pro/kohana-php-admin/database/test.db',
			'persistent' => false,
		),
		'table_prefix' => '',
		'charset'      => 'utf8',
		'caching'      => false,
	),
);

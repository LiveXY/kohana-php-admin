<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'alternate' => array(
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

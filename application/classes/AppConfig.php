<?php defined('SYSPATH') OR die('No direct script access.');

define('IsDebug', true); //false
define('BASEURI', Kohana::$base_url); //Kohana::$base_url
define('ROOTURL', rtrim(Kohana::$base_url, '/'));
define('RESOURCE', ROOTURL.'/client/');
define('RESOURCE_PATH', DOCROOT.'client/');
define('UseCache', 'memcache'); //memcache, redis
define('Crypt3DesIV', '1234567!');
define('Crypt3DesKey', '18');

define('SMTPHost', 'smtp.mailgun.org');
define('SMTPUser', '');
define('SMTPPass', '');
define('MailFrom', '');
define('MailFromName', '');

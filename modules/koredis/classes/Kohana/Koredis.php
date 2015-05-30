<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A wrapper of [phpredis](https://github.com/nicolasff/phpredis) 
 * for kohana3.x.
 *
 * @package    Koredis
 * @category   Redis
 * @author     Jeremy Wei
 * @copyright  (c) 2012 Jeremy Wei
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Kohana_Koredis {

	/**
	 * single instance of the Redis object
	 *
	 * @var Redis
	 **/
	private static $redis;

	/**
	 * Returns the singleton instance of Redis. If no instance has
	 * been created, a new instance will be created.
	 *       
	 *     $redis = Koredis::factory();
	 *
	 * @return Kohana_Koredis
	 **/
	public static function factory()
	{
		if (!Kohana_Koredis::$redis)
		{
			// Make sure Redis installed 
			if (!class_exists('Redis', FALSE))
			{
				throw new Kohana_Exception('class Redis can not be found. make sure 
					you have installed phpredis extension');
			}

			Kohana_Koredis::$redis = new Redis();

			// No config file found
			if (!Kohana::$config->load('koredis'))
			{	
				Kohana_Koredis::$redis->pconnect('127.0.0.1', 6379, 1);
			}
			else
			{
				// Load config
				$config = Kohana::$config->load('koredis');

				$host     = isset($config['host']) && ($config['host']) ? $config['host'] : '127.0.0.1'; 
				$port     = isset($config['port']) && ($config['port']) ? $config['port'] : 6379;
				$timeout  = isset($config['timeout']) && ($config['timeout']) ? $config['timeout'] : 1;
				$pconnect = isset($config['pconnect']) && ($config['pconnect']) ? $config['pconnect'] : false;

				// Persistent connection
				if ($pconnect === TRUE)
				{
					Kohana_Koredis::$redis->pconnect($host, $port, $timeout);
				}
				// Non persistent connection
				else
				{
					Kohana_Koredis::$redis->connect($host, $port, $timeout);
				}
			}
		}

		return Kohana_Koredis::$redis;
	}
}

<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
     'memcache' => array(
             'driver' 		=> 'memcache',
             'servers'		=> array(
                    array(
                         'host'       => '127.0.0.1',
                         'port'       => 11211,
                         'persistent' => TRUE
                    )
             ),
			'instant_death' => TRUE,
     		'compression'	=> FALSE,
      ),
);

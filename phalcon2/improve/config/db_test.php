<?php
/**
 * 数据库及缓存相关配置
 */
return array(
	'balanceDb' => array(
		'default' => 'tester',
		'tester' => array(
			'prefix' => 'tester_',
			'write' => array(
				'db' => array(
					'adapter'     => 'Mysql',
					'host'        => '127.0.0.1:3306',
					'username'    => 'root',
					'password'    => '',
					'dbname'      => 'tester',
					'charset'     => 'utf8',
				),
			),
			'reads' => array(
				'db1' => array(
					'adapter'     => 'Mysql',
					'host'        => '127.0.0.1:3306',
					'username'    => 'root',
					'password'    => '',
					'dbname'      => 'tester',
					'charset'     => 'utf8',
				)
			)
		),
	),
	'balanceRedis' => array(
		'main' => array(
			'write' => array(
				'main' => array(
					'host'        => '127.0.0.1',
					'port'        => '6379',
					'auth'        => 'tester',
				),
			),
			'reads' => array(
				'redis0' => array(
					'host'        => '127.0.0.1',
					'port'        => '6379',
					'auth'        => 'tester',
				)
			)
		),
	),
);

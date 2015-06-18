<?php
/**
 * 数据库及缓存相关配置
 */
return array(
	'balanceDb' => array(
		'default' => 'yingshi',
		'yingshi' => array(
			'prefix' => 'ys_',
			'write' => array(
				'db' => array(
					'adapter'     => 'Mysql',
					'host'        => '121.40.31.143:65533',
					'username'    => 'dbadmin',
					'password'    => 'dbadmin!@#143',
					'dbname'      => 'cm_yingshi2',
					'charset'     => 'utf8',
				),
			),
			'reads' => array(
				'db1' => array(
					'adapter'     => 'Mysql',
					'host'        => '121.40.31.143:65533',
					'username'    => 'dbadmin',
					'password'    => 'dbadmin!@#143',
					'dbname'      => 'cm_yingshi2',
					'charset'     => 'utf8',
				)
			)
		),
	),
	'balanceRedis' => array(
		'main' => array(
			'write' => array(
				'main' => array(
					'host'        => '121.40.31.143',
					'port'        => '6379',
					'auth'        => 'tester',
				),
			),
			'reads' => array(
				'redis0' => array(
					'host'        => '121.40.31.143',
					'port'        => '6379',
					'auth'        => 'tester',
				)
			)
		),
	),
);

<?php

$configAll = array();

//加载公用配置
$configAll = array_merge($configAll, require_once __DIR__.'/common.php');

//加载数据库配置
$configAll = array_merge($configAll, require_once __DIR__.'/db.php');

//加载业务
$configAll =  array_merge($configAll, require_once __DIR__.'/business.php');

//加载应用配置
$configAll = array_merge($configAll, array(
	'modules' => array(
		'default' => 'frontend',
		//优惠卷模块
		'frontend' => array(
			'used' => 1,
			'className' => 'Module\Frontend\Module',
			'path' => WEBROOT . '/app/Module.php',
			'defaultNamespace' => array(
				'controller' => 'Module\Frontend\Controller',
				'task' => 'Module\Frontend\Task',
			),
			'autoloadDir' => array(
				'view' => WEBROOT . '/app/view/',
			),
			'autoloadNamespace' => array(
				'Module\Frontend\Controller' => WEBROOT . '/app/controller/',
				'Module\Frontend\Task' => WEBROOT . '/app/task/',
				'Module\Frontend\View' => WEBROOT . '/app/view/',
			),
		),
	),
));

return new \Phalcon\Config($configAll);


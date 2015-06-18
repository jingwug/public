<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('WEBROOT', dirname(__DIR__));
try {

	//配置文件
	$config = include WEBROOT . "/config/config_local.php";

	//自动加载
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(
		$config->autoload->toArray()
	)->register();

	//依赖注入
	$di = new \Phalcon\DI\FactoryDefault();

	//URL	延迟加载
	$di->set('url', function () use ($config) {
		return new \Phalcon\Mvc\Url();
	}, true);

	//路由器		延迟加载
	$di->set('router', function() use($config) {
		$router = new \Phalcon\Mvc\Router();
				//加载默认模块
		$key = $config->modules->default;
		if($key && $config->modules->$key->used == 1) {
			$router->setDefaultModule('frontend');
		}
		
		return $router;
		
	}, true);

	//session	延迟加载
	$di->setShared('session', new Phalcon\Session\Adapter\Files());
	$di->get('session')->start();

	//加载DB负载均衡
	BalanceDb::config($config->balanceDb->toArray());
	foreach($config->balanceDb->toArray() as $dbKey => $currentConfig) {
		if($dbKey == 'default') continue;
		$keyWrite = 'db'.ucfirst($dbKey);
		$keyRead = $keyWrite.'_read';

		$dbWriteConfig = $currentConfig['write']['db'];
		$di->set($keyWrite, function() use($dbWriteConfig) {
			return new Phalcon\Db\Adapter\Pdo\Mysql($dbWriteConfig);
		});

		$dbReadConfig = current($currentConfig['reads']);
		$di->set($keyRead, function() use($dbReadConfig) {
			return new Phalcon\Db\Adapter\Pdo\Mysql($dbReadConfig);
		});
	}
	//设置默认数据库连接
	$defaultDbKey = 'db'.ucfirst($config->balanceDb->default);
	$di->set('db', $di->get($defaultDbKey));
	$defaultDbKey = 'db'.ucfirst($config->balanceDb->default);
	$di->set('db_read', $di->get($defaultDbKey.'_read'));

	//加载DB负载均衡
	BalanceDb::config($config->balanceDb->toArray());

	//Redis负载均衡
	BalanceRedis::config($config->balanceRedis->toArray());

	//URL 工具类
	TUrl::config($config->url->toArray());

	//config 工具类
	TConfig::instance()->setAll($config->toArray());

    //实例化应用
    $application = new \Phalcon\Mvc\Application($di);

	//注册模块
	$modules = array();
	foreach($config->modules as $key => $params) {
		if($key == 'default') continue;
		$modules[$key] = array(
			'className' => $params['className'],
			'path' => $params['path'],
		);
	}
	$application->registerModules($modules);
    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}

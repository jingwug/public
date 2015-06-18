<?php
/**
 * 测试机环境CLI模式引导文件
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(php_sapi_name() != 'cli') exit('not cli');
define('WEBROOT', dirname(__DIR__));
try {
	//接收参数
	//module/task/action是三个必填参数
	$cliData = array();
	foreach($argv as $rawValue) {
		$key = '';
		$value = '';
		if(strtolower(PHP_OS) == 'winnt') {
			if(stripos($rawValue, '=')) {
				list($key, $value) = explode('=', $rawValue);
				if(in_array($key, array('module', 'task', 'action'))) {
					$cliData[$key] = $value;
				} else {
					$cliData['params'][$key] = $value;
				}
			} else {
				//无效参数抛弃
			}
		} elseif(strtolower(PHP_OS) == 'linux') {
			if(stripos($rawValue, '=')) {
				list($key, $value) = explode('=', $rawValue);
				$key = str_replace('--', '', $key);
				if(in_array($key, array('module', 'task', 'action'))) {
					$cliData[$key] = $value;
				} else {
					$cliData['params'][$key] = $value;
				}
			} else {
				//无效参数抛弃
			}
		}
	}
	if(!isset($cliData['module']) || !isset($cliData['task']) || !isset($cliData['action'])) {
		exit("The params module/task/action is not have");
	}


	$config = include WEBROOT.'/config/console_test.php';

	//加载程序文件
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(
		$config->autoload->toArray()
	)->register();

	$di = new \Phalcon\DI\FactoryDefault\CLI();

	//配置模型元数据
	$di->set('modelsMetadata', function() {
		return new Phalcon\Mvc\Model\Metadata\Memory();
	});

	//config 工具类
	TConfig::instance()->setAll($config->toArray());

	//加载DB负载均衡
	BalanceDb::config($config->balanceDb->toArray());
	foreach($config->balanceDb->toArray() as $dbKey => $currentConfig) {
		if($dbKey == 'default') {
			continue;
		}
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

	//Redis负载均衡
	BalanceRedis::config($config->balanceRedis->toArray());

	//URL 工具类
	TUrl::config($config->url->toArray());

	$console = new Phalcon\CLI\Console();
	$console->setDI($di);
	//注册模块
	$modules = array();
	foreach($config->modules as $key => $params) {
		if($key == 'default') {
			continue;
		}
		$modules[$key] = array(
			'className' => sprintf('Module\%s\Module', ucfirst($key)),
			'path' => $params['path'],
		);
	}
	$console->registerModules($modules);
	$console->handle($cliData);

} catch(\Phalcon\Exception $e) {
	echo $e->getMessage();
}

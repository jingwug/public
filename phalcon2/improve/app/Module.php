<?php

/**
 * 优惠卷模块
 */
namespace Module\Frontend;

include WEBROOT . '/app/ModuleBase.php';

class Module extends \ModuleBase implements \Phalcon\Mvc\ModuleDefinitionInterface {

	protected $moduleId = 'frontend';

	/**
	 * 注册命名空间
	 */
	public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = NULL) {
		$config = \TConfig::instance()->getModule($this->moduleId);
		$loader = new \Phalcon\Loader();
		$loader->registerDirs(isset($config['autoloadDir']) ? $config['autoloadDir'] : array());
		$loader->registerNamespaces(isset($config['autoloadNamespace']) ? $config['autoloadNamespace'] : array());
		$loader->register();
	}

	/**
	 * 注册服务
	 * @param type $di
	 */
	public function registerServices(\Phalcon\DiInterface $di = NULL) {
		$config = \TConfig::instance()->getModule($this->moduleId);
		if(php_sapi_name() == 'cli') {
			//处理CLI模式
			$di['dispatcher'] = function() use($config){
				$dispatcher = new \Phalcon\Cli\Dispatcher();
				if(isset($config['defaultNamespace']) && isset($config['defaultNamespace']['task'])) {
					$dispatcher->setDefaultNamespace($config['defaultNamespace']);
				}
				return $dispatcher;
			};
		} else {
			//处理WEB模式
			$di['dispatcher'] = function() use($config){
				$dispatcher = new \Phalcon\Mvc\Dispatcher();
				if(isset($config['defaultNamespace']) && isset($config['defaultNamespace']['controller'])) {
					$dispatcher->setDefaultNamespace($config['defaultNamespace']['controller']);
				}
				return $dispatcher;
			};
			//添加视图
			$di->set('view', function() use($config){
				$view = new \Phalcon\Mvc\View();
				$view->setViewsDir($config['autoloadDir']['view']);
				$view->registerEngines(array(
					'.html'   => 'Phalcon\Mvc\View\Engine\Php'
				));
				return $view;
			});
		}

	}

}

<?php
/***************************************
*$File: app/tool/ToolConfig.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/27
****************************************/
class TConfig {

	public static $instance = NULL;

	private $_configs = array();

	public static function instance() {
		if(self::$instance == NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * 设置全部配置文件
	 * @param type $configs
	 * @return \ToolConfig
	 */
	public function setAll($configs = array()) {
		$this->_configs = $configs;
		return $this;
	}

	/**
	 * 添加配置文件
	 * @param string $configKey
	 * @param unknown $config
	 */
	public function set($configKey = '', $config = array()) {
		$this->_configs[$configKey] = $config;
		return $this;
	}

	/**
	 * 获取配置文件
	 * @param string $configKey
	 */
	public function get($configKey = '') {
		if(empty($configKey)) {
			return $this->_configs;
		} else {
			return isset($this->_configs[$configKey]) ? $this->_configs[$configKey] : NULL;
		}
	}

	/**
	 * 获取API参数
	 * @param type $apiId
	 */
	public function getApi($apiId = '') {
		$config = $this->get('apis');
		return isset($config[$apiId]) ? $config[$apiId] : array();
	}

	/**
	 * 获取某个模块的配置文件，一般是非业务相关的文件
	 * @param type $module
	 */
	public function getModule($moduleId = '') {
		$config = $this->get('modules');
		return isset($config[$moduleId]) ? $config[$moduleId] : array();
	}

}

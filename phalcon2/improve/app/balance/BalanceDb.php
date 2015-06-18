<?php
/***************************************
*$File: app/balance/BalanceDb.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/23
****************************************/
class BalanceDb extends BalanceDbBase {

	public static $instances = array();

	/**
	 * 单例模式，实例化
	 * @return BalanceDb
	 */
	public static function instance($dbKey = '') {
		if(self::$dbKeyDefault && !$dbKey) {
			$dbKey = self::$dbKeyDefault;
		}
		if(!isset(self::$instances[$dbKey])) {
			self::$instances[$dbKey] = new self($dbKey, self::$configs[$dbKey]);
		}
		return self::$instances[$dbKey];
	}

	/**
	 * 设置配置文件
	 * @param unknown $configs
	 */
	public static function config($configs = array()) {
		self::$configs = $configs;
		if(isset(self::$configs['default'])) {
			self::$dbKeyDefault = self::$configs['default'];
		}
		return true;
	}

	public function __construct($dbKey = '', $config = array()) {
		$this->dbKey = $dbKey;
		$this->config = $config;
		$this->initConnection();
		return $this;
	}

}

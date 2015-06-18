<?php
/***************************************
*$File: app/balance/BalanceRedis.php
*$Description:
*$Author: lideqiang
*$Time:  2015/05/01
****************************************/
class BalanceRedis extends BalanceRedisBase
{

	public static $instances = array();

	private static $configs = array();

	/**
	 * 单例模式，实例化
	 * @return BalanceRedis
	*/
	public static function instance($cacheKey = '') {
		if(!isset(self::$instances[$cacheKey])) {
			self::$instances[$cacheKey] = new self($cacheKey, self::$configs[$cacheKey]);
		}
		return self::$instances[$cacheKey];
	}
	
	/**
	 * 设置配置文件
	 * @param unknown $configs
	 */
	public static function config($configs = array()) {
		self::$configs = $configs;
		return true;
	}

	/**
	 * 构造器，实例化具体的Redis对象
	 * @param string $cacheKey
	 * @param unknown $config
	 * @return BalanceRedis
	 */
	public function __construct($cacheKey = '', $config = array()) {
		$this->cacheKey = $cacheKey;
		$this->config = $config;
		$this->initConnection();
		return $this;
	}

}

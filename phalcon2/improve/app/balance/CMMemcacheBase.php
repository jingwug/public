<?php
/***************************************
*$File: app/balance/BalanceMemcacheBase.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/23
****************************************/
class BalanceMemcacheBase extends BalanceBase
{
	protected $config = array();

	protected $cacheKey = '';

	/**
	 * 写连接
	 * @var unknown
	 */
	public $cache = NULL;

	/**
	 * 初始化连接
	 * @param string $dbKey
	 */
	public function initConnection() {
		$config = current($this->config['write']);

		$this->cache = new Memcache();

		return $this;
	}
}

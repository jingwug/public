<?php
/***************************************
*$File: app/balance/BalanceRedisBase.php
*$Description:
*$Author: lideqiang
*$Time:  2015/05/01
****************************************/
class BalanceRedisBase extends BalanceBase
{
	protected $config = array();

	protected $cacheKey = '';

	/**
	 * 写连接
	 * @var unknown
	 */
	public $cacheWrite = NULL;

	/**
	 * 读连接
	 * @var unknown
	 */
	public $cacheRead = NULL;

	/**
	 * 初始化连接
	 * @param string $dbKey
	 */
	public function initConnection() {
		$configWrite = current($this->config['write']);
		$readKey = array_rand($this->config['reads']);
		$configRead = $this->config['reads'][$readKey];

		$this->cacheWrite = new Redis();
		$this->cacheWrite->connect($configWrite['host'], $configWrite['port']);
		if(!empty($configWrite['auth'])) {
			$this->cacheWrite->auth($configWrite['auth']);
		}
		
		$this->cacheRead = new Redis();
		$this->cacheRead->connect($configRead['host'], $configRead['port']);
		if(!empty($configRead['auth'])) {
			$this->cacheRead->auth($configRead['auth']);
		}
		return $this;
	}

//=================================================	命令目录 KEY 开始 =========================================================

	/**
	 * 删除给定的一个或多个 key 
	 * @return type
	 */
	public function del() {
		$phpExecStr = '$result = $this->cacheWrite->del(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 序列化给定 key ，并返回被序列化的值，使用 RESTORE 命令可以将这个值反序列化为 Redis 键
	 * @param type $key
	 */
	public function dump($key = '') {
		return $this->cacheWrite->dump($key);
	}

	/**
	 * 检查给定的Key是否存在
	 * @param string $key
	 */
	public function exists($key = '') {
		return $this->cacheRead->exists($key);
	}

	/**
	 * 检查给定的Key是否存在
	 * @param string $key
	 */
	public function existsMain($key = '') {
		return $this->cacheWrite->exists($key);
	}	

	/**
	 * 为给定 key 设置生存时间，当 key 过期时(生存时间为 0 )，它会被自动删除
	 * @param type $key
	 * @param type $seconds
	 */
	public function expire($key = '', $seconds = 0) {
		return $this->cacheWrite->expire($key, $seconds);
	}

	/**
	 * EXPIREAT 的作用和 EXPIRE 类似，都用于为 key 设置生存时间
	 * @param type $key
	 * @param type $timestamp
	 */
	public function expireAt($key = '', $timestamp = 0) {
		return $this->cacheWrite->expireAt($key, $timestamp);
	}

	/**
	 * 获取匹配的所有Key
	 * @param string $pattern
	 */
	public function keys($pattern = '') {
		return $this->cacheRead->getKeys($pattern);
	}

	/**
	 * 从主库获取匹配的所有Key
	 * @param string $pattern
	 */
	public function keysMain($pattern = '') {
		return $this->cacheWrite->getKeys($pattern);
	}

	/**
	 * 将 key 原子性地从当前实例传送到目标实例的指定数据库上，一旦传送成功， key 保证会出现在目标实例上，而当前实例上的 key 会被删除
	 * @return type
	 */
	public function migrate() {
		$phpExecStr = '$result = $this->cacheWrite->migrate(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 将当前数据库的 key 移动到给定的数据库 db 当中
	 * @param type $key
	 * @param type $db
	 */
	public function move($key = '', $db = 1) {
		return $this->cacheWrite->move($key, $db);
	}

	/**
	 * OBJECT 命令允许从内部察看给定 key 的 Redis 对象
	 * @return type
	 */
	public function object() {
		$phpExecStr = '$result = $this->cacheRead->object(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;	
	}

	/**
	 * OBJECT 命令允许从内部察看给定 key 的 Redis 对象
	 * @return type
	 */
	public function objectMain() {
		$phpExecStr = '$result = $this->cacheWrite->object(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 移除给定 key 的生存时间，将这个 key 从『易失的』(带生存时间 key )转换成『持久的』(一个不带生存时间、永不过期的 key )
	 * @param type $key
	 */
	public function persis($key = '') {
		return $this->cacheWrite->persist($key);
	}
	
	/**
	 * 从数据库中随机返回一个Key
	 */
	public function randomKey() {
		return $this->cacheRead->randomKey();
	}

	/**
	 * 从数据库中随机返回一个Key
	 */
	public function randomKeyMain() {
		return $this->cacheWrite->randomKey();
	}

	/**
	 * 将 key 改名为 newkey 
	 * @param type $key
	 * @param type $newKey
	 */
	public function rename($key = '', $newKey = '') {
		return $this->cacheWrite->rename($key, $newKey);
	}

	/**
	 * 反序列化给定的序列化值，并将它和给定的 key 关联
	 * @return type
	 */
	public function restore() {
		$phpExecStr = '$result = $this->cacheWrite->restore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 排序
	 * @param string $key
	 * @param string $sortType	ASC | DESC
	 * @param number $isAlpha  1|0
	 */
	public function sort($key = '', $sortType = 'ASC', $isAlpha = 0) {
		if(strtoupper($sortType) == 'ASC' && $isAlpha == 0) {
			return $this->cacheRead->sortAsc($key);
		} elseif(strtoupper($sortType) == 'DESC' && $isAlpha == 0) {
			return $this->cacheRead->sortDesc($key);
		} elseif(strtoupper($sortType) == 'ASC' && $isAlpha == 1) {
			return $this->cacheRead->sortAscAlpha($key);
		} elseif(strtoupper($sortType) == 'DESC' && $isAlpha == 1) {
			return $this->cacheRead->sortDescAlpha($key);
		} else {
			return $this->cacheRead->sort($key);
		}
	}

	/**
	 * 排序
	 * @param string $key
	 * @param string $sortType	ASC | DESC
	 * @param number $isAlpha  1|0
	 */
	public function sortMain($key = '', $sortType = 'ASC', $isAlpha = 0) {
		if(strtoupper($sortType) == 'ASC' && $isAlpha == 0) {
			return $this->cacheWrite->sortAsc($key);
		} elseif(strtoupper($sortType) == 'DESC' && $isAlpha == 0) {
			return $this->cacheWrite->sortDesc($key);
		} elseif(strtoupper($sortType) == 'ASC' && $isAlpha == 1) {
			return $this->cacheWrite->sortAscAlpha($key);
		} elseif(strtoupper($sortType) == 'DESC' && $isAlpha == 1) {
			return $this->cacheWrite->sortDescAlpha($key);
		} else {
			return $this->cacheWrite->sort($key);
		}
	}

	/**
	 * 以秒为单位，返回给定 key 的剩余生存时间(TTL, time to live)
	 * @param type $key
	 * @return type
	 */
	public function ttl($key = '') {
		return $this->cacheWrite->ttl($key);
	}

	/**
	 * 获取键类型
	 * @param string $key
	 */
	public function type($key = '') {
		return $this->cacheRead->type($key);
	}

	/**
	 * 从主库获取键类型
	 * @param string $key
	 */
	public function typeMain($key = '') {
		return $this->cacheWrite->type($key);
	}

	/**
	 * SCAN 命令用于迭代当前数据库中的数据库键
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function scan(&$iterator, $pattern, $count) {
		return $this->cacheRead->scan($iterator, $pattern, $count);
	}

	/**
	 * SCAN 命令用于迭代当前数据库中的数据库键
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function scanMain(&$iterator, $pattern, $count) {
		return $this->cacheWrite->scan($iterator, $pattern, $count);
	}

//=================================================	命令目录 KEY 结束 ========================================================

//=================================================	命令目录 String 开始 =====================================================

	/**
	 * 将值追加到原值最后
	 * @param string $key
	 * @param string $value
	 */
	public function append($key = '', $value = '') {
		return $this->cacheWrite->append($key, $value);
	}

	/**
	 * 计算给定字符串中，被设置为 1 的比特位的数量
	 * @param type $key
	 * @param type $start
	 * @param type $end
	 * @return type
	 */
	public function bitcount($key = '', $start = 0, $end = 0) {
		return $this->cacheRead->bitcount($key, $start, $end);
	}

	/**
	 * 计算给定字符串中，被设置为 1 的比特位的数量
	 * @param type $key
	 * @param type $start
	 * @param type $end
	 * @return type
	 */
	public function bitcountMain($key = '', $start = 0, $end = 0) {
		return $this->cacheWrite->bitcount($key, $start, $end);
	}

	/**
	 * 对一个或多个保存二进制位的字符串 key 进行位元操作，并将结果保存到 destkey 上
	 * @return type
	 */
	public function bitop() {
		$phpExecStr = '$result = $this->cacheWrite->bitop(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 自减，默认1
	 * @param string $key
	 * @param number $decrement
	 */
	public function decr($key = '', $decrement = 1) {
		return $this->cacheWrite->decrBy($key, $decrement);
	}

	/**
	 * 获取数据
	 * @param string $key
	 */
	public function get($key = '') {
		return $this->cacheRead->get($key);
	}

	/**
	 * 获取数据
	 * @param string $key
	 */
	public function getMain($key = '') {
		return $this->cacheWrite->get($key);
	}

	/**
	 * 对 key 所储存的字符串值，获取指定偏移量上的位(bit)
	 * @param type $key
	 * @param type $offset
	 * @return type
	 */
	public function getBit($key = '', $offset = 0) {
		return $this->cacheRead->getBit($key, $offset);
	}

	/**
	 * 对 key 所储存的字符串值，获取指定偏移量上的位(bit)
	 * @param type $key
	 * @param type $offset
	 * @return type
	 */
	public function getBitMain($key = '', $offset = 0) {
		return $this->cacheWrite->getBit($key, $offset);
	}

	/**
	 * 返回 key 中字符串值的子字符串，字符串的截取范围由 start 和 end 两个偏移量决定(包括 start 和 end 在内)
	 * @param type $key
	 * @param type $start
	 * @param type $end
	 * @return type
	 */
	public function getRange($key = '', $start = 0, $end = 0) {
		return $this->cacheRead->getRange($key, $start, $end);
	}

	/**
	 * 返回 key 中字符串值的子字符串，字符串的截取范围由 start 和 end 两个偏移量决定(包括 start 和 end 在内)
	 * @param type $key
	 * @param type $start
	 * @param type $end
	 * @return type
	 */
	public function getRangeMain($key = '', $start = 0, $end = 0) {
		return $this->cacheWrite->getRange($key, $start, $end);
	}

	/**
	 * 将给定 key 的值设为 value ，并返回 key 的旧值(old value)。
	 * @param string $key
	 * @param string $value
	 */
	public function getSet ($key = '', $value = '') {
		return $this->cacheWrite->getSet($key, $value);
	}

	/**
	 * 实现自增
	 * @param string $key
	 * @param number $increment
	 */
	public function incr($key = '', $increment = 1) {
		return $this->cacheWrite->incrBy($key, $increment);
	}

	/**
	 * 浮点数自增
	 * @param unknown $key
	 * @param unknown $increment
	 */
	public function incrByFloat ($key = '', $increment = 0.1) {
		return $this->cacheWrite->incrByFloat($key, $increment);
	}

	/**
	 * 返回所有(一个或多个)给定 key 的值
	 * @return type
	 */
	public function mget() {
		$phpExecStr = '$result = $this->cacheRead->mget(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回所有(一个或多个)给定 key 的值
	 * @return type
	 */
	public function mgetMain() {
		$phpExecStr = '$result = $this->cacheWrite->mget(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 同时设置一个或多个 key-value 对
	 * @return type
	 */
	public function mset() {
		$phpExecStr = '$result = $this->cacheWrite->mset(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 设置
	 * @param string $key
	 * @param string $value
	 * @param unknown $expire
	 */
	public function set($key = '', $value = '', $expire = 0) {
		return $this->cacheWrite->set($key, $value, $expire);
	}

	/**
	 * 对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)
	 * @param type $key
	 * @param type $offset
	 * @param type $value
	 * @return type
	 */
	public function setBit($key = '', $offset = 0, $value = '') {
		return $this->cacheWrite->setBit($key, $offset, $value);
	}

	/**
	 * 用 value 参数覆写(overwrite)给定 key 所储存的字符串值，从偏移量 offset 开始
	 * @param type $key
	 * @param type $offset
	 * @param type $value
	 * @return type
	 */
	public function setRange($key = '', $offset = 0, $value = '') {
		return $this->cacheWrite->setRange($key, $offset, $value);
	}

	/**
	 * 获取字符串长度
	 * @param string $key
	 */
	public function strlen($key = '') {
		return $this->cacheRead->strlen($key);
	}

	/**
	 * 从主库获取字符串长度
	 * @param string $key
	 */
	public function strlenMain($key = '') {
		return $this->cacheWrite->strlen($key);
	}

//=================================================	命令目录 String	结束 ==================================================

//=================================================	命令目录 Hash 开始 =====================================================

	/**
	 * 删除哈希表 key 中的一个或多个指定域，不存在的域将被忽略
	 * @return type
	 */
	public function hDel() {
		$phpExecStr = '$result = $this->cacheWrite->hDel(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 查看哈希表 key 中，给定域 field 是否存在
	 * @param type $key
	 * @param type $field
	 */
	public function hExists($key = '', $field = '') {
		return $this->cacheRead->hExists($key, $field);
	}

	/**
	 * 查看哈希表 key 中，给定域 field 是否存在
	 * @param type $key
	 * @param type $field
	 */
	public function hExistsMain($key = '', $field = '') {
		return $this->cacheWrite->hExists($key, $field);
	}

	/**
	 * 返回哈希表 key 中给定域 field 的值
	 * @param type $key
	 * @param type $field
	 */
	public function hGet($key = '', $field = '') {
		return $this->cacheRead->hGet($key, $field);
	}

	/**
	 * 返回哈希表 key 中给定域 field 的值
	 * @param type $key
	 * @param type $field
	 */
	public function hGetMain($key = '', $field = '') {
		return $this->cacheWrite->hGet($key, $field);
	}

	/**
	 * 返回哈希表 key 中，所有的域和值
	 * @param type $key
	 */
	public function hGetAll($key = '') {
		return $this->cacheRead->hGetAll($key);
	}

	/**
	 * 返回哈希表 key 中，所有的域和值
	 * @param type $key
	 */
	public function hGetAllMain($key = '') {
		return $this->cacheWrite->hGetAll($key);
	}

	/**
	 * 为哈希表 key 中的域 field 的值加上增量 increment 
	 * @param type $key
	 * @param type $field
	 * @param type $increment
	 */
	public function hIncrBy($key = '', $field = '', $increment = 1) {
		return $this->cacheWrite->hIncrBy($key, $field, $increment);
	}

	/**
	 * 为哈希表 key 中的域 field 加上浮点数增量 increment 
	 * @param type $key
	 * @param type $field
	 * @param type $increment
	 */
	public function hIncrByFloat($key = '', $field = '', $increment = 0.1) {
		return $this->cacheWrite->hIncrByFloat($key, $field, $increment);
	}

	/**
	 * 返回哈希表 key 中的所有域
	 * @param type $key
	 */
	public function hKeys($key = '') {
		return $this->cacheRead->hKeys($key);
	}

	/**
	 * 返回哈希表 key 中的所有域
	 * @param type $key
	 */
	public function hKeysMain($key = '') {
		return $this->cacheWrite->hKeys($key);
	}

	/**
	 * 返回哈希表 key 中域的数量
	 * @param type $key
	 */
	public function hLen($key = '') {
		return $this->cacheRead->hLen($key);
	}

	/**
	 * 返回哈希表 key 中域的数量
	 * @param type $key
	 */
	public function hLenMain($key = '') {
		return $this->cacheWrite->hLen($key);
	}

	/**
	 * 返回哈希表 key 中，一个或多个给定域的值
	 * @return type
	 */
	public function hMget() {
		$phpExecStr = '$result = $this->cacheRead->hMget(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回哈希表 key 中，一个或多个给定域的值
	 * @return type
	 */
	public function hMgetMain() {
		$phpExecStr = '$result = $this->cacheWrite->hMget(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 同时将多个 field-value (域-值)对设置到哈希表 key 中
	 * @return type
	 */
	public function hMset() {
		$phpExecStr = '$result = $this->cacheWrite->hMset(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 将哈希表 key 中的域 field 的值设为 value 
	 * @param type $key
	 * @param type $field
	 * @param type $value
	 */
	public function hSet($key = '', $field = '', $value = '') {
		return $this->cacheWrite->hSet($key, $field, $value);
	}

	/**
	 * 返回哈希表 key 中所有域的值
	 * @param type $key
	 * @return type
	 */
	public function hVals($key = '') {
		return $this->cacheRead->hVals($key);
	}

	/**
	 * 返回哈希表 key 中所有域的值
	 * @param type $key
	 * @return type
	 */
	public function hValsMain($key = '') {
		return $this->cacheWrite->hVals($key);
	}

	/**
	 * 用于迭代哈希键中的键值对
	 * @param type $key
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function hscan($key = '', $iterator = '', $pattern = '', $count = 0) {
		return $this->cacheRead->hscan($key, $iterator, $pattern, $count);
	}

	/**
	 * 用于迭代哈希键中的键值对
	 * @param type $key
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function hscanMain($key = '', $iterator = '', $pattern = '', $count = 0) {
		return $this->cacheWrite->hscan($key, $iterator, $pattern, $count);
	}

//=================================================	命令目录 Hash 开始 ==================================================

//=================================================	命令目录 List 开始 =====================================================

	/**
	 * BLPOP 是列表的阻塞式(blocking)弹出原语
	 * @return type
	 */
	public function blPop() {
		$phpExecStr = '$result = $this->cacheWrite->blPop(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * BLPOP 是列表的阻塞式(blocking)弹出原语
	 * @return type
	 */
	public function brPop() {
		$phpExecStr = '$result = $this->cacheWrite->brPop(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * BRPOPLPUSH 是 RPOPLPUSH 的阻塞版本，当给定列表 source 不为空时， BRPOPLPUSH 的表现和 RPOPLPUSH 一样
	 * @param type $source
	 * @param type $destination
	 * @param type $timeout
	 * @return type
	 */
	public function brpoplpush($source = '', $destination = '', $timeout = 0) {
		return $this->cacheWrite->brpoplpush($source, $destination, $timeout);
	}

	/**
	 * 返回列表 key 中，下标为 index 的元素
	 * @param type $key
	 * @param type $index
	 * @return type
	 */
	public function lindex($key = '', $index = 0) {
		return $this->cacheRead->lindex($key, $index);
	}

	/**
	 * 将值 value 插入到列表 key 当中，位于值 pivot 之前或之后
	 * @param string $key
	 * @param string $position	BEFORE | AFTER
	 * @param string $pivot
	 * @param string $value
	 */
	public function lInsert($key = '', $position = 'BEFORE', $pivot = '', $value = '') {
		$position = $position == 'BEFORE' ? 'BEFORE' : 'AFTER';
		return $this->cacheWrite->lInsert($key, $position, $pivot, $value);
	}

	/**
	 * 返回列表 key 的长度
	 * @param type $key
	 */
	public function lLen($key = '') {
		return $this->cacheRead->lLen($key);
	}

	/**
	 * 返回列表 key 的长度
	 * @param type $key
	 */
	public function lLenMain($key = '') {
		return $this->cacheWrite->lLen($key);
	}

	/**
	 * 移除并返回列表 key 的头元素
	 * @param string $key
	 */
	public function lPop($key = '') {
		return $this->cacheWrite->lPop($key);
	}

	/**
	 * 将一个或多个值 value 插入到列表 key 的表头
	 * @return unknown
	 */
	public function lPush() {
		$phpExecStr = '$result = $this->cacheWrite->lPush(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回列表 key 中指定区间内的元素，区间以偏移量 start 和 stop 指定
	 * @param type $key
	 * @param type $start
	 * @param type $stop
	 * @return type
	 */
	public function lrange($key = '', $start = 0, $stop = -1) {
		return $this->cacheRead->lrange($key, $start, $stop);
	}

	/**
	 * 返回列表 key 中指定区间内的元素，区间以偏移量 start 和 stop 指定
	 * @param type $key
	 * @param type $start
	 * @param type $stop
	 * @return type
	 */
	public function lrangeMain($key = '', $start = 0, $stop = -1) {
		return $this->cacheWrite->lrange($key, $start, $stop);
	}

	/**
	 * 根据参数 count 的值，移除列表中与参数 value 相等的元素
	 * @param type $key
	 * @param type $count
	 * @param type $value
	 * @return type
	 */
	public function lrem($key = '', $count = 0, $value = '') {
		return $this->cacheWrite->lrem($key, $count, $value);
	}

	/**
	 * 将列表 key 下标为 index 的元素的值设置为 value
	 * @param string $key
	 * @param number $index
	 * @param string $value
	 */
	public function lSet($key = '', $index = 0, $value = '') {
		return $this->cacheWrite->lSet($key, $index, $value);
	}

	/**
	 * 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除
	 * @param type $key
	 * @param type $start
	 * @param type $stop
	 * @return type
	 */
	public function ltrim($key = '', $start = 0, $stop = -1) {
		return $this->cacheWrite->ltrim($key, $start, $stop);
	} 
	
	/**
	 * 移除并返回列表 key 的尾元素
	 * @param string $key
	 */
	public function rPop($key = '') {
		return $this->cacheWrite->rPop($key);
	}	
	
	/**
	 * 命令 RPOPLPUSH 在一个原子时间内，执行两个动作
	 * @param type $source
	 * @param type $destination
	 * @return type
	 */
	public function rpoplpush($source = '', $destination = '') {
		return $this->cacheWrite->rpoplpush($source, $destination);
	}

	/**
	 * 将一个或多个值 value 插入到列表 key 的表尾(最右边)
	 * @return unknown
	 */
	public function rPush () {
		$phpExecStr = '$result = $this->cacheWrite->rPush(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

//=================================================	命令目录 List 结束 ========================================================

//=================================================	命令目录 Set 开始 =====================================================	

	/**
	 * 将一个或多个 member 元素加入到集合 key 当中，已经存在于集合的 member 元素将被忽略
	 */
	public function sAdd() {
		$phpExecStr = '$result = $this->cacheWrite->sAdd(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}
	
	/**
	 * 返回集合 key 的基数(集合中元素的数量)
	 * @param type $key
	 * @return type
	 */
	public function scard($key = '') {
		return $this->cacheRead->scard($key);
	}

	/**
	 * 返回集合 key 的基数(集合中元素的数量)
	 * @param type $key
	 * @return type
	 */
	public function scardMain($key = '') {
		return $this->cacheWrite->scard($key);
	}	
	
	/**
	 * 返回一个集合的全部成员，该集合是所有给定集合之间的差集
	 * @return type
	 */
	public function sDiff() {
		$phpExecStr = '$result = $this->cacheRead->sDiff(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}
	
	/**
	 * 返回一个集合的全部成员，该集合是所有给定集合之间的差集
	 * @return type
	 */
	public function sDiffMain() {
		$phpExecStr = '$result = $this->cacheWrite->sDiff(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}	
	
	/**
	 * 这个命令的作用和 SDIFF 类似，但它将结果保存到 destination 集合，而不是简单地返回结果集
	 * @return type
	 */
	public function sDiffStore() {
		$phpExecStr = '$result = $this->cacheWrite->sDiffStore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;		
	}	

	/**
	 * 返回一个集合的全部成员，该集合是所有给定集合的交集
	 * @return type
	 */
	public function sInter() {
		$phpExecStr = '$result = $this->cacheWrite->sInter(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 这个命令类似于 SINTER 命令，但它将结果保存到 destination 集合，而不是简单地返回结果集
	 * @return type
	 */
	public function sInterStore() {
		$phpExecStr = '$result = $this->cacheWrite->sInterStore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 判断 member 元素是否集合 key 的成员
	 * @param type $key
	 * @param type $member
	 * @return type
	 */
	public function sismember($key = '', $member = '') {
		return $this->cacheRead->sismember($key, $member);
	}

	/**
	 * 判断 member 元素是否集合 key 的成员
	 * @param type $key
	 * @param type $member
	 * @return type
	 */
	public function sismemberMain($key = '', $member = '') {
		return $this->cacheWrite->sismember($key, $member);
	}

	/**
	 * 返回集合 key 中的所有成员
	 * @param type $key
	 */
	public function sMembers($key = '') {
		return $this->cacheRead->sMembers($key);
	}	
	
	/**
	 * 返回集合 key 中的所有成员
	 * @param type $key
	 */
	public function sMembersMain($key = '') {
		return $this->cacheWrite->sMembers($key);
	}	

	/**
	 * 将 member 元素从 source 集合移动到 destination 集合
	 * @param string $source
	 * @param string $destination
	 * @param string $member
	 */
	public function sMove($source = '', $destination ='', $member = '') {
		return $this->cacheWrite->sMove($source, $destination, $member);
	}
	
	/**
	 * 移除并返回集合中的一个随机元素
	 * @param string $key
	 */
	public function sPop($key = '') {
		return $this->cacheWrite->sPop($key);
	}	
	
	/**
	 * 如果命令执行时，只提供了 key 参数，那么返回集合中的一个随机元素
	 * @param string $key
	 * @param number $count
	 */
	public function sRandMember($key = '', $count = 1) {
		return $this->cacheRead->sRandMember($key, $count);
	}	
	
	/**
	 * 如果命令执行时，只提供了 key 参数，那么返回集合中的一个随机元素
	 * @param string $key
	 * @param number $count
	 */
	public function sRandMemberMain($key = '', $count = 1) {
		return $this->cacheWrite->sRandMember($key, $count);
	}	
	
	/**
	 * 移除集合 key 中的一个或多个 member 元素，不存在的 member 元素会被忽略
	 * @return type
	 */
	public function srem() {
		$phpExecStr = '$result = $this->cacheWrite->srem(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}	
	
	/**
	 * 返回一个集合的全部成员，该集合是所有给定集合的并集
	 * @return type
	 */
	public function sUnion() {
		$phpExecStr = '$result = $this->cacheRead->sUnion(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回一个集合的全部成员，该集合是所有给定集合的并集
	 * @return type
	 */
	public function sUnionMain() {
		$phpExecStr = '$result = $this->cacheWrite->sUnion(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 这个命令类似于 SUNION 命令，但它将结果保存到 destination 集合，而不是简单地返回结果集
	 * @return type
	 */
	public function sUnionStore() {
		$phpExecStr = '$result = $this->cacheWrite->sUnionStore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;		
	}	

	/**
	 * SSCAN 命令用于迭代集合键中的元素
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function sscan(&$iterator, $pattern, $count) {
		return $this->cacheRead->sscan($iterator, $pattern, $count);
	}

	/**
	 * SSCAN 命令用于迭代集合键中的元素
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function sscanMain(&$iterator, $pattern, $count) {
		return $this->cacheWrite->sscan($iterator, $pattern, $count);
	}

//=================================================	命令目录 Set 结束 ========================================================

//=================================================	命令目录 SortedSet 开始 ==================================================

	/**
	 * 将一个或多个 member 元素及其 score 值加入到有序集 key 当中
	 * @return type
	 */
	public function zAdd() {
		$phpExecStr = '$result = $this->cacheWrite->zAdd(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 的基数
	 * @param type $key
	 */
	public function zCard($key = '') {
		return $this->cacheRead->zCard($key);
	}

	/**
	 * 返回有序集 key 的基数
	 * @param type $key
	 */
	public function zCardMain($key = '') {
		return $this->cacheWrite->zCard($key);
	}

		/**
	 * 返回有序集 key 中， score 值在 min 和 max 之间(默认包括 score 值等于 min 或 max )的成员的数量
	 * @param type $key
	 * @param type $min
	 * @param type $max
	 */
	public function zCount($key = '', $min = 0, $max = -1) {
		return $this->cacheRead->zount($key, $min, $max);
	}
	
		/**
	 * 返回有序集 key 中， score 值在 min 和 max 之间(默认包括 score 值等于 min 或 max )的成员的数量
	 * @param type $key
	 * @param type $min
	 * @param type $max
	 */
	public function zCountMain($key = '', $min = 0, $max = -1) {
		return $this->cacheWrite->zCount($key, $min, $max);
	}

	/**
	 * 为有序集 key 的成员 member 的 score 值加上增量 increment 
	 * @param type $key
	 * @param type $increment
	 * @param type $member
	 */
	public function zIncrBy($key = '', $increment = 0,  $member = '') {
		return $this->cacheWrite->zIncrBy($key, $increment, $member);
	}

	/**
	 * 返回有序集 key 中，指定区间内的成员
	 * @return type
	 */
	public function zRange() {
		$phpExecStr = '$result = $this->cacheRead->zRange(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 中，指定区间内的成员
	 * @return type
	 */
	public function zRangeMain() {
		$phpExecStr = '$result = $this->cacheWrite->zRange(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 中，所有 score 值介于 min 和 max 之间(包括等于 min 或 max )的成员。有序集成员按 score 值递增(从小到大)次序排列
	 * @return type
	 */
	public function zRangeByScore() {
		$phpExecStr = '$result = $this->cacheRead->zRangeByScore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 中，所有 score 值介于 min 和 max 之间(包括等于 min 或 max )的成员。有序集成员按 score 值递增(从小到大)次序排列
	 * @return type
	 */
	public function zRangeByScoreMain() {
		$phpExecStr = '$result = $this->cacheWrite->zRangeByScore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 中成员 member 的排名。其中有序集成员按 score 值递增(从小到大)顺序排列
	 * @param type $key
	 * @param type $member
	 */
	public function zRank($key = '', $member = '') {
		return $this->cacheRead->zRank($key, $member);
	}

	/**
	 * 返回有序集 key 中成员 member 的排名。其中有序集成员按 score 值递增(从小到大)顺序排列
	 * @param type $key
	 * @param type $member
	 */
	public function zRankMain($key = '', $member = '') {
		return $this->cacheWrite->zRank($key, $member);
	}

	/**
	 * 移除有序集 key 中的一个或多个成员，不存在的成员将被忽略
	 * @return type
	 */
	public function zRem() {
		$phpExecStr = '$result = $this->cacheWrite->zRem(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 移除有序集 key 中，指定排名(rank)区间内的所有成员
	 * @param type $key
	 * @param type $start
	 * @param type $stop
	 * @return type
	 */
	public function zRemRangeByRank($key = '', $start = 0, $stop = 3) {
		return $this->cacheWrite->zRemRangeByRank($key, $start, $stop);
	}

	/**
	 * 移除有序集 key 中，所有 score 值介于 min 和 max 之间(包括等于 min 或 max )的成员
	 * @param type $key
	 * @param type $min
	 * @param type $max
	 * @return type
	 */
	public function zRemRangeByScore($key = '', $min = 0, $max = -1) {
		return $this->cacheWrite->zRemRangeByScore($key, $min, $max);
	}

	/**
	 * 返回有序集 key 中成员 member 的排名。其中有序集成员按 score 值递减(从大到小)排序
	 * @param type $key
	 * @param type $member
	 */
	public function zRevRank($key = '', $member = '') {
		return $this->cacheRead->zRank($key, $member);
	}

	/**
	 * 返回有序集 key 中成员 member 的排名。其中有序集成员按 score 值递减(从大到小)排序
	 * @param type $key
	 * @param type $member
	 */
	public function zRevRankMain($key = '', $member = '') {
		return $this->cacheWrite->zRank($key, $member);
	}

		/**
	 * 返回有序集 key 中， score 值介于 max 和 min 之间(默认包括等于 max 或 min )的所有的成员。
	 * 有序集成员按 score 值递减(从大到小)的次序排列
	 * @return type
	 */
	public function zRevRangeByScore() {
		$phpExecStr = '$result = $this->cacheRead->zRevRangeByScore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 中， score 值介于 max 和 min 之间(默认包括等于 max 或 min )的所有的成员。
	 * 有序集成员按 score 值递减(从大到小)的次序排列
	 * @return type
	 */
	public function zRevRangeByScoreMain() {
		$phpExecStr = '$result = $this->cacheWrite->zRevRangeByScore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 返回有序集 key 中，成员 member 的 score 值
	 * @param type $key
	 * @param type $member
	 */
	public function zScore($key = '', $member = '') {
		return $this->cacheRead->zScore($key, $member);
	}

	/**
	 * 返回有序集 key 中，成员 member 的 score 值
	 * @param type $key
	 * @param type $member
	 */
	public function zScoreMain($key = '', $member = '') {
		return $this->cacheWrite->zScore($key, $member);
	}

	/**
	 * 计算给定的一个或多个有序集的并集，其中给定 key 的数量必须以 numkeys 参数指定，并将该并集(结果集)储存到 destination 
	 * @return type
	 */
	public function zunionstore() {
		$phpExecStr = '$result = $this->cacheWrite->zunionstore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * 计算给定的一个或多个有序集的交集，其中给定 key 的数量必须以 numkeys 参数指定，并将该交集(结果集)储存到 destination 
	 * @return type
	 */
	public function zinterstore() {
		$phpExecStr = '$result = $this->cacheWrite->zinterstore(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

	/**
	 * SCAN 命令用于迭代当前数据库中的数据库键
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function zscan(&$iterator, $pattern, $count) {
		return $this->cacheRead->zscan($iterator, $pattern, $count);
	}

	/**
	 * SCAN 命令用于迭代当前数据库中的数据库键
	 * @param type $iterator
	 * @param type $pattern
	 * @param type $count
	 * @return type
	 */
	public function zscanMain(&$iterator, $pattern, $count) {
		return $this->cacheWrite->zscan($iterator, $pattern, $count);
	}

//=================================================	命令目录 SourtedSet 结束 ==============================================

//=================================================	命令目录 Transaction 开始 =============================================

	/**
	 * 取消事务，放弃执行事务块内的所有命令
	 * @return type
	 */
	public function discard() {
		return $this->cacheWrite->discard();
	}

	/**
	 * 执行所有事务块内的命令
	 * @return type
	 */
	public function exec() {
		return $this->cacheWrite->exec();
	}

	/**
	 * 标记一个事务块的开始
	 * @return type
	 */
	public function multi() {
		return $this->cacheWrite->multi();
	}

	/**
	 * 取消 WATCH 命令对所有 key 的监视
	 * @return type
	 */
	public function unwatch() {
		return $this->cacheWrite->unwatch();
	}

	/**
	 * 监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断
	 * @return type
	 */
	public function watch() {
		$phpExecStr = '$result = $this->cacheWrite->watch(\''.implode("','", func_get_args())."');";
		eval("$phpExecStr");
		return $result;
	}

//=================================================	命令目录 Transaction 结束 ==============================================

//=================================================	命令目录 Connection 开始 =============================================

	/**
	 * 通过设置配置文件中 requirepass 项的值(使用命令 CONFIG SET requirepass password )，可以使用密码来保护 Redis 服务器
	 * @param type $password
	 * @return type
	 */
	public function auth($password) {
		return $this->cacheRead->auth($password);
	}

	/**
	 * 通过设置配置文件中 requirepass 项的值(使用命令 CONFIG SET requirepass password )，可以使用密码来保护 Redis 服务器
	 * @param type $password
	 * @return type
	 */
	public function authMain($password) {
		return $this->cacheWrite->auth($password);
	}

	/**
	 * 使用客户端向 Redis 服务器发送一个 PING ，如果服务器运作正常的话，会返回一个 PONG
	 * @return type
	 */
	public function ping() {
		return $this->cacheRead->ping();
	}

	/**
	 * 使用客户端向 Redis 服务器发送一个 PING ，如果服务器运作正常的话，会返回一个 PONG
	 * @return type
	 */
	public function pingMain() {
		return $this->cacheWrite->ping();
	}
	
	/**
	 * 请求服务器关闭与当前客户端的连接
	 * @return type
	 */
	public function quit() {
		return $this->cacheRead->quit();
	}

	/**
	 * 请求服务器关闭与当前客户端的连接
	 * @return type
	 */
	public function quitMain() {
		return $this->cacheWrite->quit();
	}

	/**
	 * 切换到指定的数据库，数据库索引号 index 用数字值指定，以 0 作为起始索引值
	 * @param type $index
	 * @return type
	 */
	public function select($index) {
		return $this->cacheRead->select($index);
	}
	
	/**
	 * 切换到指定的数据库，数据库索引号 index 用数字值指定，以 0 作为起始索引值
	 * @param type $index
	 * @return type
	 */
	public function selectMain($index) {
		return $this->cacheWrite->select($index);
	}
	
//=================================================	命令目录 Connection 结束 ==============================================

//=================================================	命令目录 Server 开始 =============================================
	
	/**
	 * 执行一个 AOF文件 重写操作。重写会创建一个当前 AOF 文件的体积优化版本
	 * @return type
	 */
	public function bgrewriteaof() {
		return $this->cacheWrite->bgrewriteaof();
	}
	
	/**
	 * 在后台异步(Asynchronously)保存当前数据库的数据到磁盘
	 * @return type
	 */
	public function bgSave() {
		return $this->cacheWrite->bgSave();
	}
	
	/**
	 * 返回当前数据库的 key 的数量
	 * @return type
	 */
	public function dbSize() {
		return $this->cacheWrite->dbSize();
	}
	
	/**
	 * 清空整个 Redis 服务器的数据(删除所有数据库的所有 key )
	 * @return type
	 */
	public function flushAll() {
		return $this->cacheWrite->flushAll();
	}	
	
	/**
	 * 清空当前数据库中的所有 key
	 * @return type
	 */
	public function flushDB() {
		return $this->cacheWrite->flushDB();
	}
	
	/**
	 * 以一种易于解释（parse）且易于阅读的格式，返回关于 Redis 服务器的各种信息和统计数值
	 * @param type $section
	 */
	public function info($section = '') {
		return $this->cacheRead->info($section);
	}

	/**
	 * 以一种易于解释（parse）且易于阅读的格式，返回关于 Redis 服务器的各种信息和统计数值
	 * @param type $section
	 */
	public function infoMain($section = '') {
		return $this->cacheWrite->info($section);
	}
	
	/**
	 * 返回最近一次 Redis 成功将数据保存到磁盘上的时间，以 UNIX 时间戳格式表示
	 * @return type
	 */
	public function lastSave() {
		return $this->cacheWrite->lastSave();
	}

	/**
	 * SAVE 命令执行一个同步保存操作，将当前 Redis 实例的所有数据快照(snapshot)以 RDB 文件的形式保存到硬
	 * @return type
	 */
	public function save() {
		return $this->cacheWrite->save();
	}

	/**
	 * SLAVEOF 命令用于在 Redis 运行时动态地修改复制(replication)功能的行为
	 * @return type
	 */
	public function slaveof() {
		return $this->cacheRead->slaveof();
	}

	/**
	 * SLAVEOF 命令用于在 Redis 运行时动态地修改复制(replication)功能的行为
	 * @return type
	 */
	public function slaveofMain() {
		return $this->cacheMain->slaveof();
	}	

	/**
	 * 返回当前服务器时间
	 * @return type
	 */
	public function time() {
		return $this->cacheRead->time();
	}

	/**
	 * 返回当前服务器时间
	 * @return type
	 */
	public function timeMain() {
		return $this->cacheWrite->time();
	}

}

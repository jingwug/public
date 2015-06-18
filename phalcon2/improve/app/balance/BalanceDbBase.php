<?php
/***************************************
*$File: app/balance/BalanceDb.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/23
****************************************/
class BalanceDbBase extends BalanceBase
{

	protected $config = array();

	protected $dbKey = '';
	static protected $configs = array();
	static protected $dbKeyDefault = '';

	/**
	 * 写连接
	 * @var unknown
	 */
	public $connectionWrite = NULL;

	/**
	 * 读连接
	 * @var unknown
	 */
	public $connectionRead = NULL;

	/**
	 * 初始化连接
	 * @param string $dbKey
	 */
	public function initConnection() {
		$readKey = array_rand($this->config['reads']);
		$this->connectionWrite = new Phalcon\Db\Adapter\Pdo\Mysql(current($this->config['write']));
		$this->connectionRead = new Phalcon\Db\Adapter\Pdo\Mysql($this->config['reads'][$readKey]);
		return $this;
	}

	/**
	 * 从从库查询一条数据
	 * @param string $sql
	 * @param unknown $bindParams
	 * @param unknown $bindTypes
	 */
	public function fetchOne($sql = '', $bindParams = array(), $bindTypes = array()) {
		return $this->connectionRead->fetchOne($sql, Phalcon\Db::FETCH_ASSOC, $bindParams, $bindTypes);
	}

	/**
	 * 从主库查询一条数据
	 * @param string $sql
	 * @param unknown $bindParams
	 * @param unknown $bindTypes
	 */
	public function fetchOneMain($sql = '', $bindParams = array(), $bindTypes = array()) {
		return $this->connectionWrite->fetchOne($sql, Phalcon\Db::FETCH_ASSOC, $bindParams, $bindTypes);
	}

	/**
	 * 从从库查询符合条件的所有数据
	 * @param unknown $sql
	 * @param unknown $bindParams
	 * @param unknown $bindTypes
	 */
	public function fetchAll($sql = '', $bindParams = array(), $bindTypes = NULL) {
		return $this->connectionRead->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, $bindParams, $bindTypes);
	}

	/**
	 * 从主库查询符合条件的所有记录
	 * @param string $sql
	 * @param unknown $bindParams
	 * @param unknown $bindTypes
	 */
	public function fetchAllMain($sql = '', $bindParams = array(), $bindTypes = array()) {
		return $this->connectionWrite->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, $bindParams, $bindTypes);
	}

	/**
	 * 查询某个字段
	 * @param string $sql
	 * @param unknown $placeholders
	 * @param unknown $column
	 */
	public function fetchColumn($sql = '', $placeholders = array(), $column = array()) {
		return $this->connectionRead->fetchColumn($sql, $placeholders, $column);
	}

	/**
	 * 查询某个字段
	 * @param string $sql
	 * @param unknown $placeholders
	 * @param unknown $column
	 */
	public function fetchColumnMain($sql = '', $placeholders = array(), $column = array()) {
		return $this->connectionWrite->fetchColumn($sql, $placeholders, $column);
	}

	/**
	 * 插入数据
	 * @param string $table
	 * @param unknown $data
	 * @param unknown $dataTypes
	 */
	public function insert($table = '', $data = array(), $dataTypes = array()) {
		$sql = "insert into `{$table}` (`". implode("`,`", array_keys($data)) ."`) values(:".implode(",:", array_keys($data)).")";
		$result = $this->connectionWrite->execute($sql, $data);
		return $result ? $this->connectionWrite->lastInsertId() : false;
	}

	/**
	 * 更新单表数据
	 * @param string $table
	 * @param array $data
	 * @param array $whereCondition
	 * @param array $dataTypes
	 */
	public function update($table = '', $data = array(), $whereCondition = array(), $dataTypes = array()) {
		return $this->connectionWrite->updateAsDict($table, $data, $whereCondition, $dataTypes);
	}

	/**
	 * 删除数据
	 * @param string $table
	 * @param unknown $whereCondition
	 * @param unknown $placeholders
	 * @param unknown $dataTypes
	 */
	public function delete($table = '', $whereCondition = array(), $placeholders = array(), $dataTypes = array()) {
		return $this->connectionWrite->delete($table, $whereCondition, $placeholders, $dataTypes);
	}

	/**
	 * 执行SQL
	 * @param string $sql
	 * @param unknown $bindParams
	 * @param unknown $bindTypes
	 */
	public function execute($sql = '', $bindParams = array(), $bindTypes = array()) {
		return $this->connectionWrite->execute($sql, $bindParams, $bindTypes);
	}

	/**
	 * 开启事务
	 */
	public function begin() {
		return $this->connectionWrite->begin();
	}

	/**
	 * 回滚事务
	 */
	public function rollback() {
		return $this->connectionWrite->rollback();
	}

	/**
	 * 提交事务
	 */
	public function commit() {
		return $this->connectionWrite->commit();
	}

	/**
	 * 获取数据更新影响的条数
	 */
	public function affectedRows() {
		return $this->connectionWrite->affectedRows();
	}

	/**
	 * 插入最后插入的记录ID
	 */
	public function lastInsertId() {
		return $this->connectionWrite->lastInsertId();
	}

}

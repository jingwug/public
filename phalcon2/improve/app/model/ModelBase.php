<?php
/**
 * 模型基类
 * @author lideqiang
 */
class ModelBase extends \Phalcon\Mvc\Model {

	/**
	 * 表前缀
	 * @var type 
	 */
	protected $tablePrefix = 'ys_';

	public function initialize() {
		$this->setWriteConnectionService('db');
		$this->setReadConnectionService('db_read');
		$table = $this->tablePrefix.$this->getSource();
		$this->setSource($table);

		//取消某项功能，下面这些功能默认为开启的，可关闭
		$this->setup(array(
//			'events' => false,				//事件
//			'columnRenaming' => false,		//字段重命名
			'notNullValidations'=> false,	//验证字段非空
//			'virtualForeignKeys' => false,	//验证外键
//			'phqlLiterals' => false,		//关闭自带的SQL分析器 phql
		));
	}

	/**
	 * 重置字段NULL值
	 * @return \ModelBase
	 */
	protected function resetFieldNull() {
		$fileds = $this->toArray();
		foreach($fileds as $field => $value) {
			if($value == NULL) {
				$this->$field = '';
			}
		}
		return true;
	}

	/**
	 * 保存数据之前
	 * @return type
	 */
	public function beforeSave() {
		return $this->resetFieldNull();
	}

	/**
	 * 创建数据之前
	 * @return type
	 */
	public function beforeCreate() {
		return $this->resetFieldNull();
	}

	/**
	 * 更新数据之前
	 * @return type
	 */
	public function beforeUpdate() {
		return $this->resetFieldNull();
	}

}

<?php
/**
 * ģ�ͻ���
 * @author lideqiang
 */
class ModelBase extends \Phalcon\Mvc\Model {

	/**
	 * ��ǰ׺
	 * @var type 
	 */
	protected $tablePrefix = 'ys_';

	public function initialize() {
		$this->setWriteConnectionService('db');
		$this->setReadConnectionService('db_read');
		$table = $this->tablePrefix.$this->getSource();
		$this->setSource($table);

		//ȡ��ĳ��ܣ�������Щ����Ĭ��Ϊ�����ģ��ɹر�
		$this->setup(array(
//			'events' => false,				//�¼�
//			'columnRenaming' => false,		//�ֶ�������
			'notNullValidations'=> false,	//��֤�ֶηǿ�
//			'virtualForeignKeys' => false,	//��֤���
//			'phqlLiterals' => false,		//�ر��Դ���SQL������ phql
		));
	}

	/**
	 * �����ֶ�NULLֵ
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
	 * ��������֮ǰ
	 * @return type
	 */
	public function beforeSave() {
		return $this->resetFieldNull();
	}

	/**
	 * ��������֮ǰ
	 * @return type
	 */
	public function beforeCreate() {
		return $this->resetFieldNull();
	}

	/**
	 * ��������֮ǰ
	 * @return type
	 */
	public function beforeUpdate() {
		return $this->resetFieldNull();
	}

}

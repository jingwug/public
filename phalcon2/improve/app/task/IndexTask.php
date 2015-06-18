<?php
namespace Module\Frontend\Task;

/**
 * CLI模式 默认任务类
 * 它本身是一个controller
 * 
 */
class IndexTask extends TaskBase {

	public function mainAction() {
		echo "\nThis is the default task and the default action \n";
	}

	/**
	 * 测试首个请求
	 */
	public function indexAction() {
		$row = \BalanceDb::instance('db')->fetchOne('select now() as mysqlNow');
		echo $row ? "mysqNow is {$row['mysqlNow']}" : "sql is error";
		echo "\n";
		echo 'jingwu tester';
	}

}

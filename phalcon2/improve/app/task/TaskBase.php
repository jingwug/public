<?php
namespace Module\Frontend\Task;

/**
 * CLI模式 任务处理基类
 */
class TaskBase extends \Phalcon\CLI\Task {
	/**
	 * 
	 */
	public function mainAction() {
		echo "\nThis is the default task and the default action \n";
	}

}

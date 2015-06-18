<?php
namespace Module\Frontend\Task;

/**
 * CLI模式 默认任务类
 * 它本向是一个controller
 * 
 */
class MainTask extends TaskBase
{
	public function mainAction() {
		echo "\nThis is the default task and the default action \n";
	}

}

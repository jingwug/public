<?php
namespace Module\Frontend\Controller;

/***************************************
 *$File: app/controllers/UserController.php
 *$Description:
 *$Author: lideqiang
 *$Time:  2015/06/05
 ****************************************/
class IndexController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
		$this->view->setVar('title', '欢迎进入进阶系统');
	}

	/*
	 * 首页
	 */
	public function indexAction() {

	}

}

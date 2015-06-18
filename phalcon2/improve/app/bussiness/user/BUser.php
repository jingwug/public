<?php
/***************************************
*$File: app/controllers/BUser.php
*$Description:
*$Author: lideqiang
*$Time:  2015/06/14
****************************************/
 class BUser extends BussinessBase {

	/**
	 * 单例模式，实例化对象
	 * @var type 
	 */
	public static $instance = NULL;

	/**
	 * 单例模式，实例化对象
	 * @return type
	 */
	public static function instance() {
		if(self::$instance == NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

 }
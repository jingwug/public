<?php
/***************************************
*$File: Captcha.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/21
****************************************/
class Captcha extends CaptchaBase {

	public static $instances = array();
	private $type = 'image';				//image/sms

	/**
	 * 单例模式，实例化类
	 */
	public static function install($type = 'image') {
		$classname = 'Captcha'.ucfirst($type);
		if(!isset(self::$instances[$type])) {
			self::$instances[$type] = new $classname();
		}
		return self::$instances[$type];
	}

}

<?php
/***************************************
*$File: captcha/CaptchaBase.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/19
****************************************/
class CaptchaBase {

	protected $distrubCode = '1235467890qwertyuipkjhgfdaszxcvbnmQWERTYUIPKJHGFDASZXCVBNM';

	/**
	 * 初始化操作
	 */
	public function __construct() {
	}

	/**
	 * 保存验证码
	 * @param string $operateId
	 * @param array $data
	 * @param number $expire
	 */
	protected function setCaptcha($operateId = '', $data = array(), $expire = 60) {
		$_SESSION[$operateId] = $data;
		return $this;
	}

	/**
	 * 根据操作ID获取验证码
	 * @param string $operateId
	 */
	protected function getCaptcha($operateId = '') {
		return isset($_SESSION[$operateId]) ? $_SESSION[$operateId] : array();
	}

	protected function createCaptcha($num = 4) {
		$length = strlen($this->distrubCode);
		$captchas = array();
		for($j = 0; $j < $num; $j++) {
			$position = rand(0,$length - 1);
			$captchas[] = $this->distrubCode[$position];
		}
		return implode($captchas);
	}
	
	/*
	 * 创建操作ID
	 */
	protected function createOperateId() {
		return md5(microtime().rand(1, 10000).rand(1, 10000));
	}

}

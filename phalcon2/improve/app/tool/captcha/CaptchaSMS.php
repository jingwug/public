<?php
/***************************************
*$File: captcha/CaptchaSMS.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/22
****************************************/
class CaptchaSMS extends CaptchaBase implements ICaptcha {

	public function __construct() {
	}

	/**
	 * 生成验证码，默认有效期60秒
	 * @see ICaptcha::generate()
	 */
	public function generate($expire = 60) {
	}

	/**
	 * 验证正确性
	 * @see ICaptcha::verify()
	 */
	public function verify($operateId = '', $captcha = '') {
	}

	/**
	 * 根据 operateId 获取验证码
	 * @see CaptchaBase::getCaptcha()
	 */
	public function getCaptcha($operateId = '') {
	}

}

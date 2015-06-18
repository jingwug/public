<?php
/***************************************
*$File: captcha/ICaptcha.php
*$Description: 验证码接口，定义行为
*$Author: lideqiang
*$Time:  2015/4/21
****************************************/
interface ICaptcha {

	/**
	 * 生成验证码，
	 * @param int $num 生成数量
	 * @param int $expire 有效期，以秒为单位
	 */
	public function generate($expire = 60);

	/**
	 * 验证，判断验证码是否有效
	 * @param string $operateId
	 * @param string $code
	 */
	public function verify($operateId = '', $captcha = '');

	/**
	 * 根据操作ID获取验证码
	 * @param string $operateId
	 */
	public function getCaptcha($operateId = '');

}

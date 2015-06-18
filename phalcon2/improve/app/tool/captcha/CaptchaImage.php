<?php
/***************************************
*$File: captcha/CaptchaImage.php
*$Description:
*$Author: lideqiang
*$Time:  2015/4/19
****************************************/
class CaptchaImage extends CaptchaBase implements ICaptcha {

	private $font = '';
	private $width = 100;
	private $height = 40;
	private $wordNum = 4;

	public function __construct() {
		$this->font = __DIR__.'/imgcode.ttf';
	}

	/**
	 * 设置图片宽度
	 * @param number $width
	 * @return CaptchaImage
	 */
	public function setWidth($width = 100) {
		$this->width = $width;
		return $this;
	}

	/**
	 * 设置图片高度
	 * @param number $height
	 * @return CaptchaImage
	 */
	public function setHeight($height = 30) {
		$this->height = $height;
		return $this;
	}

	/**
	 * 设置字长
	 * @param number $wordNum
	 * @return CaptchaImage
	 */
	public function setWordnum($wordNum = 4) {
		$this->wordNum = $wordNum;
		return $this;
	}
	
	/**
	 * 生成验证码，默认有效期60秒
	 * @see ICaptcha::generate()
	 */
	public function generate($expire = 60) {
		$captcha = $this->createCaptcha($this->wordNum);
		$operateId = $this->createOperateId();
		$key = 'captcha_'.$operateId;
		$_SESSION[$key] = array(
			'captcha' => $captcha,
			'width' => $this->width,
			'height' => $this->height,
			'num' => $this->wordNum,
		);
		return array('operateId' => $operateId, 'captcha' => $captcha);
	}

	/**
	 * 验证正确性
	 * @see ICaptcha::verify()
	 */
	public function verify($operateId = '', $captcha = '') {
		$data = $this->getCaptcha($operateId);
		return strtolower($data['captcha']) == strtolower($captcha) ? true : false;
	}

	/**
	 * 根据 operateId 获取验证码
	 * @see CaptchaBase::getCaptcha()
	 */
	public function getCaptcha($operateId = '') {
		$key = 'captcha_'.$operateId;
		return isset($_SESSION[$key]) ? $_SESSION[$key] : array();
	}
	

	/**
	 * 生成图片
	 * @param string $operateId
	 */
	public function image($operateId = '') {
		$data = $this->getCaptcha($operateId);
		
		$width = $data['width'];
		$height = $data['height'];
		$wordNum = $data['num'];
		$captcha = $data['captcha'];

		$font = $this->font;
		header("Content-type: image/JPEG");
		$imgHandle = imagecreate($width, $height);
		$bgcolor = ImageColorAllocate($imgHandle, rand(200,255),rand(200,255),rand(200,255));
		imagefill($imgHandle, 0, 0, $bgcolor);

		$y = floor($height/2) + floor($height/4);
		$fontsize = rand(18,22);              //修改了验证码字体大小，解决验证码遮盖问题 @hk
		//$font = './imgcode.ttf';
		for($i=0; $i < $wordNum; $i++) {
			$char = $captcha[$i];
			$x=floor($width/$wordNum)*$i+4;  //将验证码内容往做移动了3个像素，初始是7 @hk
			$jiaodu=rand(-20,30);
			$color = ImageColorAllocate($imgHandle,rand(0,50),rand(50,100),rand(100,140));
			imagettftext($imgHandle,$fontsize,$jiaodu,$x,$y,$color,$font,$char);
		}
		
		$cou=floor($height);
		$cou = 10;
		for($i=0;$i<$cou;$i++){
			$x=rand(0, $width);
			$y=rand(0,$height);
			$jiaodu=rand(0,180);
			$fontsize=rand(6,12);
			$originalcode = '!@#￥%';
			$countdistrub = strlen($originalcode);
		
			$dscode = $originalcode[rand(0,$countdistrub-1)];
			$color = ImageColorAllocate($imgHandle, rand(40,140),rand(40,140),rand(40,140));
			imagettftext($imgHandle,$fontsize,$jiaodu,$x,$y,$color,$font,$dscode);
		}
		ImageGIF($imgHandle);
		ImageDestroy($imgHandle);
		exit;
	}

}


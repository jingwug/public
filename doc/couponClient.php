<?php

$api = 'http://test.coupon.caimiao.com/api/coupon';
$signKey = '123456';
$appid = 'tester';

$couponClient = CouponClient::instance()->setApi($api)->setSignKey($signKey)->setAppid($appid);

//生成抵用卷
$params = array(
	'generate' => array(
		'type_code' => '10001',
	),
);
$result = $couponClient->requestApi($params);
var_dump($result);exit;
/*
//查询抵用卷
$params = array(
	'queryCoupon' => array(
		'code' => '10001201506131732010060387',
	),
);
$result = $couponClient->requestApi($params);
var_dump($result);exit;
*/
/*
//使用抵用卷
$params = array(
	'useCoupon' => array(
		'code' => '10001201506131732010060387',
		'amount_use' => '10',
	),
);
$result = $couponClient->requestApi($params);
var_dump($result);exit;
*/

class CouponClient {

	//API地址
	private $_api = '';

	//签名密钥
	private $_signKey = '';

	//分配给请求应用的ID
	private $_appid = '';

	/**
	 * 单例模式
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

	/**
	 * 设置API地址
	 * @param type $api
	 * @return \CouponClient
	 */
	public function setApi($api = '') {
		$this->_api = $api;
		return $this;
	}

	/**
	 * 设置签名Key
	 * @param type $signKey
	 * @return \CouponClient
	 */
	public function setSignKey($signKey = '') {
		$this->_signKey = $signKey;
		return $this;
	}

	/**
	 * 设置APPID
	 * @param type $appid
	 * @return \CouponClient
	 */
	public function setAppid($appid = '') {
		$this->_appid = $appid;
		return $this;
	}

	/**
	 * 数据签名
	 * @param type $json
	 * @param type $signKey
	 * @return type
	 */
	public function signature($json = '', $signKey = '') {
		return md5($json.$signKey);
	}

	/**
	 * 请求API接口
	 * @param type $params
	 * @return string
	 */
	public function requestApi($params = array()) {
		$response = array('status' => 0, 'msg' => '', 'data' => array());
		$json = json_encode($params);
		$sign = md5($json.$this->_signKey);
		$postData = array(
			'sign' => $sign,
			'appid' => $this->_appid,
			'data' => $json,
		);
		$result = $this->post($this->_api, $postData);
		if($result['httpCode'] == '200') {
			$jsonResult = json_decode($result['output'], 1);
			if($jsonResult) {
				if($jsonResult['status']) {
					$response['status'] = 1;
					$response['msg'] = 'ok';
					$response['data'] = $jsonResult['data'];
				} else {
					$response['msg'] = $jsonResult['msg'];
				}
				return $response;
			} else {
				$response['msg'] = 'json decode error';
				return $response;
			}
		} else {
			$response['msg'] = $result['error'];
			return $response;
		}
	}

	/**
	 * 发送请求
	 * @param type $url
	 * @param type $postData
	 * @return type
	 */
	public function post($url, $postData = array()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		$output = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);
		curl_close($ch);
		return array(
			'httpCode' => $httpCode, 
			'error' => $error, 
			'output' => $output,
		);
	}

}

<?php

/*
 * 针对IP库进行再次封装，
 * @Author lideqiang
 * 
*/

class TIP extends IP {
	/**
	 * 获取客户端IP
	 */
	public static function clientIp() {
		global $ip;
		if(getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} else if(getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} else if(getenv("REMOTE_ADDR")) {
			$ip = getenv("REMOTE_ADDR");
		} else {
			$ip = "Unknow";
		}
		return $ip;
	}

	/**
	 * 获取地域信息
	 * @param string $ip
	 */
	public static function region($ip = '') {
		$ip = empty($ip) ? self::clientIp() : $ip;
		return self::find($ip);
	}
	
}

?>
<?php
/**********************************************
* $File: /app/tool/TUrl.php
* $Description: URL工具类
* 
* 初始化方法：   TUrl::config($config);
* 使用方法：     TUrl::instance()->js('index.js');
* 
* $Author: lideqiang
* $Time: 2015年05月04日
***********************************************/
class TUrl extends Phalcon\MVC\URL {
    
    /**
     * URL配置
     * @var type 
     */
    public static $config = NULL;
    
    /**
     *
     * @var type 
     */
    public static $instance = NULL;

	/**
	 * 实例化类
	 * @return type
	 */
	public static function instance() {
        if(self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	/**
	 * 加载配置文件
	 * @param type $config
	 */
	public static function config($config = array()) {
		$config['version'] = isset($config['version']) ? $config['version'] : date('Ymd').'01';
		self::$config = $config;
		return self::instance();
	}
	
	/**
	 * 获取URL	path info 格式
	 * @param type $uri
	 * @param type $args
	 * @param type $local
	 */
	public function get($uri = NULL, $args = NULL, $local = NULL) {
		$url = parent::get($uri, $args, $local);
		return str_replace(array('?', '&', '='), array('/', '/', '/'), $url);
	}

	/**
	 * 获取JS的url
	 * @param type $uri
	 * @return type
	 */
	public function js($uri = '', $isVersion = 1) {
		$url = (isset(self::$config['js']) ? self::$config['js'] : '').'/'.$uri;
		return $url.($isVersion ? '?v='.self::$config['version'] : '');
	}

	/**
	 * 获取通用JS的URL
	 * @param type $uri
	 * @return type
	 */
	public function jsCommon($uri = '', $isVersion = 1) {
		$url = (isset(self::$config['jsCommon']) ? self::$config['jsCommon'] : '').'/'.$uri;
		return $url.($isVersion ? '?v='.self::$config['version'] : '');
	}

	/**
	 * 获取 CSS 文件URL
	 * @param type $uri
	 * @return type
	 */
	public function css($uri = '', $isVersion = 1) {
		$url = (isset(self::$config['css']) ? self::$config['css'] : '').'/'.$uri;
		return $url.($isVersion ? '?v='.self::$config['version'] : '');
	}

	/**
	 * 获取图片URL，系统自带的图片
	 * @param type $uri
	 * @return type
	 */
	public function image($uri = '') {
		return (isset(self::$config['image']) ? self::$config['image'] : '').'/'.$uri;
	}

	/**
	 * 获取后台上传图片URL
	 * @param type $uri
	 * @return type
	 */
	public function imageUp($uri = '') {
		return (isset(self::$config['imageUp']) ? self::$config['imageUp'] : '').'/'.$uri;
	}

	/**
	 * 获取用户头像URL
	 * @param type $uri
	 * @return type
	 */
	public function headimg($uri = '') {
		return (isset(self::$config['headimg']) ? self::$config['headimg'] : '').'/'.$uri;
	}

}

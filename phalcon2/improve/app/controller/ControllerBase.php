<?php
namespace Module\Frontend\Controller;

class ControllerBase extends \Phalcon\Mvc\Controller
{
	protected function initialize()
	{
		/**
		 * url path info 格式参数处理，除 路由规则外的参数全部入到 $_GET
		 * /film/detail/id/3120
		 * 接收方式：$_GET['id']		$this->request->getQuery('id');
		 */
		$params = $this->dispatcher->getParams();
		$sequence = (int)ceil(count($params) / 2);
		for($i = 0; $i < $sequence; $i++) {
			$key = isset($params[2 * $i]) ? $params[2 * $i] : '';
			$value = isset($params[2 * $i + 1]) ? $params[2 * $i + 1] : '';
			$_GET[$key] = $value;
		}

		// 处理登录

		$this->session->set ( 'city_parent_id', '862' );
		$this->session->set ( 'cityCode', '310100' );
		$this->session->set ( 'cityName', '上海' );
		$this->view->setVar ( 'userInfo', $this->session->get ( 'userInfo' ) );
		$this->view->setVar ( 'uid', $this->session->get ( 'uid' ) ? $this->session->get ( 'uid' ) : '' );
	}

}

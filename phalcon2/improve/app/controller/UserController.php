<?php
namespace Module\Frontend\Controller;

/***************************************
 *$File: app/controllers/UserController.php
 *$Description:
 *$Author: lideqiang
 *$Time:  2015/06/05
 ****************************************/
class UserController extends ControllerBase
{
	public function initialize() {
		parent::initialize();
		//用户未登录，记录来源
		if (!$this->session->get('uid') && !$this->session->get('referer')) {
			$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $this->url->get('/');
			$this->session->set('referer', $referer);
		}
	}

	/*
	 * 会员中心页面
	 */
	public function indexAction() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		if (empty($userInfo)) {
			$url = $this->url->get('user/login');
			header("Location:$url");
			exit;
		}
		$uid  = $userInfo['id'];
		//分页
		$page = $this->request->get('page')?$this->request->get('page'):1;
		$filter = array("uid=$uid",'order'=>'ctime desc');
		//总数
		$count = \Order::count($filter);
		$limit = 5;
		$filter['offset'] = ($page-1)*$limit;
		$filter['limit'] = $limit;
		//查询订单信息
		$userOrder = \Order::find($filter) ? \Order::find($filter)->toArray():'';

		//查询订单影片信息
		foreach($userOrder as &$val) {
			$val['film'] 			= Film::findFirst('id='.$val['film_id'])->toArray();
			$val['film_heat'] 		= FilmHeat::findFirst('film_id='.$val['film_id'])->toArray();
			$schedule  				= FilmSchedule::findFirst('id='.$val['schedule_id'])->toArray();
			if($schedule){
				$time 		= strtotime($schedule['starttime']);
				$week_arr 	= array('日','一','二','三','四','五','六');
				$val['film_schedule'] = date('n',$time).'月'.date('j',$time).'日 周'.$week_arr[date('w',$time)].' '.date('H:i',$time);
			}else{
				$val['film_schedule'] = '';
			}

		}

		$page_html = \ToolStr::GetPage($count,$limit);
		$this->view->setVar('page_html', $page_html);
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('user_order', $userOrder);
		$this->view->setVar('title', '会员中心');

	}


	/*
	 * 跳转到登录页面
	 */
	public function loginAction() {
		//验证会员是否已登录
		if ($this->session->get('uid')) {
			$url = $this->url->get('user/index');
			header("Location:$url");
			exit;
		}
		$url = strpos($_SERVER['HTTP_REFERER'],'yingshi')?$_SERVER['HTTP_REFERER']:'';
		if($url){
			$this->session->set('referer',$url);
		}
		$account = '';
		if (isset($_COOKIE['account']) && !empty($_COOKIE['account'])) {
			$account = $_COOKIE['account'];
		}
		$this->view->setVar('account', $account);
		$this->view->setVar('title', '会员登录');
		$this->view->setVar('flg', '1');

	}

	/*
	 * 跳转到登录页面
	 */
	public function registerAction() {
		//验证会员是否已登录
		if ($this->session->get('uid')) {
			$url = $this->url->get('user/index');
			header("Location:$url");
			exit;
		}
		$url = strpos($_SERVER['HTTP_REFERER'],'yingshi')?$_SERVER['HTTP_REFERER']:'';
		if($url){
			$this->session->set('referer',$url);
		}
		$this->view->setVar('account', '');
		$this->view->setVar('title', '会员注册');
		$this->view->setVar('flg', '2');
		$this->view->pick('user/login');

	}

	/*
	 * 退出登录
	 */
	public function loginOutAction() {
		$this->session->remove('uid');
		$this->session->remove('userInfo');
		$url = @$_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->url->get('/');;
		header("Location:$url");
		exit;
	}


	/*
	 * 会员账户余额明细
	 */
	public function surplusAction() {
		//验证会员是否已登录
		$result = \BUser::instance()->checkLogin();
		$userInfo 		= $this->session->get('userInfo');
		$uid 			= $this->session->get('uid');
		$page			= $this->request->get('page')?$this->request->get('page'):1;

		//分页
		$filter = array("uid = $uid",'order'=>'ctime DESC');
		//总数
		$count = \UserAccountLog::count($filter);
		$limit = 5;

		$filter['offset'] = ($page-1)*$limit;
		$filter['limit'] = $limit;
		$account_log	= \UserAccountLog::find($filter)->toArray();

		$page_html = \ToolStr::GetPage($count,$limit);
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('page_html', $page_html);
		$this->view->setVar('account_log', $account_log);
		$this->view->setVar('title', '账户明细');
	}
	/*
	 * 会员充值
	 */
	public function rechargeAction() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		$result = \BUser::instance()->checkLogin();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('title', '会员充值');

	}
	/*
	 * 会员基本资料
	 */
	public function basicAction() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		\BUser::instance()->checkLogin();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('title', '基本资料');
	}

	/*
	 * 会员联系方式
	 */
	public function contactAction() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		\BUser::instance()->checkLogin();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('title', '联系方式');
	}

	/*
	 * 会员安全设置
	 */
	public function safeAction() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		\BUser::instance()->checkLogin();
		$safeLevel = \BUser::instance()->safeLevel();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('safeLevel', $safeLevel);
		$this->view->setVar('title', '安全设置');
	}

	/*
	 * 修改密码第一步
	 */
	public function changePwd1Action() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		\BUser::instance()->checkLogin();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('title', '修改登录密码');
	}

	/*
	 * 修改密码第二步
	 */
	public function changePwd2Action() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		\BUser::instance()->checkLogin();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('title', '修改登录密码');
	}

	/*
	 * 修改密码第三步
	 */
	public function changePwd3Action() {
		$userInfo = $this->session->get('userInfo');
		//验证会员是否已登录
		\BUser::instance()->checkLogin();
		$this->view->setVar('userInfo', $userInfo);
		$this->view->setVar('title', '修改登录密码');
	}

	/*
	 * 发送邮件
	 */
	public function sendEmailAction() {
		$result = \ToolEmail::sendmail('397941962@qq.com','注册邮件','测试注册邮件','');
		die($result);
	}



	/*
	 * ajax登录
	 */
	public function doLoginAction() {
		$login = $this->request->getPost('data') ? $this->request->getPost('data') : '';
		if (!$login['username']) {
			\ToolFlash::error("请输入帐号");
		}
		if (!$login['password']) {
			\ToolFlash::error("请输入密码");
		}
		if (!$login['captcha']) {
			\ToolFlash::error("请输入验证码");
		}

		if(!\Captcha::install()->verify($this->session->get('captachaID'), $login['captcha'])){
			\ToolFlash::error("验证码不正确");
		}
		$login['is_remember'] = $this->request->getPost('is_remember');
		//获取帐号类型

		$login['accountType'] = \BUser::instance()->getAccountType($login['username']);

		if (!$login['accountType']) {
			\ToolFlash::error("请输入正确的帐号");
		}

		//验证帐号密码是否OK
		if ($userInfo = \BUser::instance()->login($login)) {

			\BUser::instance()->setLoginSession($userInfo, $login); //登陆后会话信息处理
			//跳转到上个页面
			$url = $this->session->get('referer');
			$this->session->remove('referer');
			\ToolFlash::success("登录成功",$url);
		} else {
			\ToolFlash::error("请输入正确的帐号或密码");
		}
	}

	/*
	 * 注册用户
	 */
	public function signUpAction() {
		$signUp = $this->request->getPost('data');
		$signUp['username'] = $signUp['phone'];
		if(!$signUp['phone'] || !$signUp['code'] || !$signUp['password'] || !$signUp['repassword']) {
			\ToolFlash::error('请填写完注册信息');
		}

		if(!\ToolValidator::isMobile($signUp['phone'])) {
			\ToolFlash::error('请填写正确的手机号码');
		}

		//判断该用户是否存在

		if(\BUser::instance()->phoneExists($signUp['phone'])) {
			\ToolFlash::error('手机号已存在');
		}

		if($signUp['password'] != $signUp['repassword']) {
			\ToolFlash::error('两次输入密码不一致');
		}

		$smsInfo = $this->session->get('sms_info');
		if($signUp['code'] != $smsInfo['code']) {
            \ToolFlash::error('请输入正确的手机验证码');
		}

		if(!\Captcha::install()->verify($this->session->get('captachaID'), $signUp['captcha'])){
			\ToolFlash::error("验证码不正确");
		}
		$userData = array(
			'username' => $signUp['username'],
			'phone' => $signUp['phone'],
			'password' => $signUp['password'],
		);
		if(\BUser::instance()->saveUser($userData)) {
			//注册成功保存session
			$userInfo = \User::findFirst("username='".$userData['phone']."'")->toArray();
			\BUser::instance()->setLoginSession($userInfo);
			//跳转到上个页面
			$url = $this->session->get('referer');
			$this->session->remove('referer');
			$url = $url ? $url : $this->url->getBaseUri();

			/******注册成功送5000测试账户余额  add by zhangyanan 20150504  start 正式上线后删除*****/
			$UserAccount = new \UserAccount();
			$UserAccountLog = new \UserAccountLog();
			$UserAccount->save(array('uid'=>$userInfo['id'],'account'=>200000,'income'=>200000,'expense'=>0,'ctime'=>time()));
			$UserAccountLog->save(array('uid'=>$userInfo['id'],'type'=>1,'amount'=>200000,'note'=>'注册测试金额2000','ctime'=>time()));
			/******注册成功送5000测试账户余额  add by zhangyanan 20150504  end 正式上线后删除*****/

			\ToolFlash::success('注册成功',$url);
		} else {
			\ToolFlash::error('注册失败，请重新注册');
		};
	}

	/*
	 * 给用户发送手机验证码
	 */
	public function sendSmsAction() {
		if ($this->request->isAjax()) {
			$phone = $this->request->getPost('phone');
		} else {
			\ToolFlash::error("请正确提交表单");
		}

		if (!\ToolValidator::isMobile($phone)) {
			\ToolFlash::error("请填写正确的手机号码");
		}
		if (\User::findFirst("phone = ".$phone)) {
			\ToolFlash::error("手机号已存在");
		}
		//生成手机验证码
		$code = \ToolSMS::getSMS();
		$sms_info_last = $this->session->get('sms_info');
		$interval = time() - $sms_info_last['send_time'];
		if ($interval < 60) {
			$interval = 60 - $interval;
			\ToolFlash::error("请于" . $interval . "秒后再次发送");
		}
		$sms_info = [
			'code' => $code,
			'send_time' => time(),
			//'times'=>$sms_info_last['times']+1,
		];
		$this->session->set("sms_info", $sms_info);

		$res = \ToolSMS::send($code, $phone);
		$res = \ToolXml::xmlToArray($res);
		if ($res['returnstatus'] == 'Success') {
			\ToolFlash::success("短信发送成功");
		} else {
			\ToolFlash::error("短信发送失败");
		}

	}


	/*
	 * 用户登录后发送手机验证码
	 */
	public function sendUserSmsAction() {
		if ($this->request->isAjax()) {
			$userInfo = $this->session->get('userInfo');
			$phone = $userInfo['phone'];
		} else {
			\ToolFlash::error("禁止直接调用");
		}
		if(empty($phone)){
			\ToolFlash::error("获取用户手机号失败，请联系客服");
		}

		//生成手机验证码
		$code = \ToolSMS::getSMS();
		$sms_info_last = $this->session->get('sms_info');
		$interval = time() - $sms_info_last['send_time'];
		if ($interval < 60) {
			$interval = 60 - $interval;
			\ToolFlash::error("请于" . $interval . "秒后再次发送");
		}
		$sms_info = [
			'code' => $code,
			'send_time' => time(),
			//'times'=>$sms_info_last['times']+1,
		];
		$this->session->set("sms_info", $sms_info);

		$res =\ ToolSMS::send($code, $phone);
		$res = \ToolXml::xmlToArray($res);
		if ($res['returnstatus'] == 'Success') {
			\ToolFlash::success("短信发送成功");
		} else {
			\ToolFlash::error("短信发送失败");
		}

	}

	/*
	 * 修改用户信息
	 */
	public function editAjaxAction() {
		\BUser::instance()->checkLogin();
		if ($this->request->isAjax()) {
			$headimg 	= $this->request->getPost('headimg','string')?$this->request->getPost('headimg','string'):'';
			$sex 		= $this->request->getPost('sex','string')?$this->request->getPost('sex','string'):'';
			$qq 		= $this->request->getPost('qq','string')?$this->request->getPost('qq','string'):'';
			$email 		= $this->request->getPost('email','string')?$this->request->getPost('email','string'):'';
			$nickname	= $this->request->getPost('nickname','string')?$this->request->getPost('nickname','string'):'';
		}
		$uid = $this->session->get('uid');
		$userInfo = \User::findFirst("id = $uid");
		//imgbase64转URL
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $headimg, $result)){
			$type = $result[2];
			$new_file = __DIR__ . '/../../public/upload/head/haed_'.$uid.'.'.$type;
			if(!file_exists($new_file)) {	    //如果文件不存在（默认为当前目录下）
				$fh = fopen($new_file,"w");
				fclose($fh);		    //关闭文件
			}
			if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $headimg)))){
				$new_file = '/upload/head/haed_'.$uid.'.'.$type;
			}else{
				$new_file = '';
			}
		}else{
			$new_file = '';
		}

		$sql = 'UPDATE `ys_user` SET ';
		if($userInfo){
			if($new_file){
				$sql .= "headimg = '".$new_file."',";
			}
			if($sex){
				$sql .= "sex = '".$sex."',";
			}
			if($qq){
				if(!is_numeric($qq)){
					\ToolFlash::error("请输入正确的QQ号码");
				}
				if(\User::findFirst("id != $uid AND qq=$qq")){
					\ToolFlash::error("QQ号码已存在");
				}
				$sql .= "qq = '".$qq."',";
			}
			if($email){
				if(!\ToolCheck::is_email($email)){
					\ToolFlash::error("邮箱格式不正确");
				}
				if(\User::findFirst("id != $uid AND email='$email'")){
					\ToolFlash::error("邮箱已存在");
				}
				$sql .= "email = '".$email."',";

			}
			if($nickname){
				$sql .= "nickname = '".$nickname."',";
			}
			$sql .= "utime = '".time()."' WHERE id = $uid";
			$db = \BalanceDb::instance();
			if($db->execute($sql)){
				$userInfo = \User::findFirst("id = $uid");
				\BUser::instance()->setLoginSession($userInfo->toArray());
				\ToolFlash::success("修改成功");
			}else{
				foreach ($userInfo->getMessages() as $message) {
					error_log(print_r($message,true));
				}
				\ToolFlash::error("修改失败");
			}
		}else{
			\ToolFlash::error("找不到用户信息");
		}

	}

	//用户名检查
	public function checkNameAction() {
		$userName = $this->request->getPost('username') ? $this->request->getPost('username') : '';
		$status = 0;
		if (\ToolValidator::isMobile($userName)) { //手机号注册
			$status = 1;
		} elseif (\ToolValidator::isEmail($userName)) { //邮箱注册
			$status = 2;
		}

		die(json_encode($status));
	}

	//验证码检查
	public function checkCaptchaAction() {
		$captcha = $this->request->getPost('captcha') ? $this->request->getPost('captcha') : '';
		if($captcha){
			if(\Captcha::install()->verify($this->session->get('captachaID'), $captcha)){
				die(json_encode(1));
			}else{
				die(json_encode(0));
			}
		}else{
			die(json_encode(0));
		}

	}

	//检查用户是否登录
	public function isLoginAction() {
		if( $this->session->get('uid')){
			die(json_encode(1));
		}else{
			die(json_encode(0));
		}

	}

	//手机验证码检查
	public function checkPhoneCodeAction() {
		$captcha = $this->request->getPost('captcha') ? $this->request->getPost('captcha') : '';
		if($captcha){
			if(\Captcha::install()->verify($this->session->get('captachaID'), $captcha)){
				die(json_encode(1));
			}else{
				die(json_encode(0));
			}
		}else{
			die(json_encode(0));
		}

	}

	/**
	 * 绑定邮箱
	 */
	public function bindMailAction() {
		$userInfo = \Phalcon\DI::getDefault()->getSession()->get('userInfo');
		$this->view->setVar('userInfo', $userInfo);
	}

	/**
	 * 绑定手机号
	 */
	public function bindPhoneAction() {
		
	}

	/**
	 * 设置支付密码
	 */
	public function setPayPasswordAction() {
		
	}

}
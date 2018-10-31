<?php

/**
* 用于向微信小程序用户发送消息
**/

class WechatspSendMsg{
	private $wechatcfg = null;

	public function __construct($wecfg){
		$this->wechatcfg = $wecfg;

		$this->CI =& get_instance();
		$this->CI->load->helper('curl_get');
		$this->CI->load->helper('curl_post');
	}

	/**
	* +--------------------------------------------------------------
	* 获取access_token
	* +--------------------------------------------------------------
	*/
	private function getAccessToken(){
		$wechatcfg = $this->wechatcfg;
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$wechatcfg['appid']."&secret=".$wechatcfg['secret'];
		return curl_get($url);
	}

	// 1. 通用的发送函数，只能内部调用，用于执行发送任务；
	public function send($data, $token=null){
		$wechatcfg = $this->wechatcfg;

		$flag  = 0;
		
		if(empty($token)){
			$redis = RedisInstance::getInstance();
			$key   = ENVIRONMENT.':WECHAT:'.$wechatcfg['name'].':access_token';
			
			$token = $redis->get($key);
		}
		
		if(!$token){
			$json = $this->getAccessToken();
			$json = json_decode($json,true);
			if(isset($json['access_token'])){
				$flag = 1;
				$token = $json['access_token'];

				$redis->set($key, $token);
				$redis->expire($key, 7100);
			}
		}else{
			$flag = 1;
		}

		if($flag){
			//var_dump($token);
			//$token = 'P7Tlo22u1cMRkQQchpdG5tELsBchap6Mt4bWqYeQnaodGtRcGhDu73mldmtWGbZmxYGH5j6yTh62mfXfGBi09rOe65Diwk1DTj3ZycMSyod45Nko8lYkVnK_B7zJI4FHVSEjAFANKW';
			$url  = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$token;
			$data =  json_encode($data,JSON_UNESCAPED_UNICODE);
			$ret  = curl_post($url, $data);
			return json_decode($ret,true);
		}else{
			return ['errcode'=>9999,'errmsg'=>$json['errmsg']];
		}
	}
	
	// 发送文本消息：
	public function sendText($toUser, $formid, $content){
	  return $this->send($toUser, 'text', array('content' => $content));
	}
	
	// // 3. 发送消息给管理员：
	// public function sendTextToAdmin($content){
	// 	$admArray = [];
	// 	foreach($admArray as $adm){
	// 		$this->sendText($adm, "客服消息：" . date("Y-m-d H:i:s"). "\n" . $content);
	// 	}
	// 	return 1;
	// }
	
	// // 4. 发送消息给客服：
	// public function sendTextToKefu($content){
	// 	$kefuArray = [];
	// 	foreach($kefuArray as $kefu){
	// 		$this->sendText($kefu, "客服消息：" . date("Y-m-d H:i:s"). "\n" . $content);
	// 	}
	// 	return 1;
	// }
	
	// // 5. 发送图片给指定用户：
	// public function sendImage($toUser, $media_id){
	// 	return $this->send($toUser, 'image', array('media_id' => $media_id));
	// }

}


<?php

/**
* 客服类，用于向微信关注用户发送消息， 客户消息在用户操作过公众号48小时内有效；
**/
class WeixinKefu {

	public function __construct() {
		$CI =& get_instance();
		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');
	}

	// 1. 通用的发送函数，只能内部调用，用于执行发送任务；
	private function send($toUser, $msgType, $data){
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".wechat_token();
		$json = json_encode(
			array(
				'touser' => $toUser,
				'msgtype' => $msgType,
				$msgType => $data
			), JSON_UNESCAPED_UNICODE);
		$ret = curl_post($url, $json);
		return $ret;
	}
	
	// 2. 发送文本消息：
	public function sendText($toUser, $content){
	  return $this->send($toUser, 'text', array('content' => $content));
	}
	
	// 3. 发送消息给管理员：
	public function sendTextToAdmin($content){
		$admArray = [];
		foreach($admArray as $adm){
			$this->sendText($adm, "客服消息：" . date("Y-m-d H:i:s"). "\n" . $content);
		}
		return 1;
	}
	
	// 4. 发送消息给客服：
	public function sendTextToKefu($content){
		$kefuArray = [];
		foreach($kefuArray as $kefu){
			$this->sendText($kefu, "客服消息：" . date("Y-m-d H:i:s"). "\n" . $content);
		}
		return 1;
	}
	
	// 5. 发送图片给指定用户：
	public function sendImage($toUser, $media_id){
		return $this->send($toUser, 'image', array('media_id' => $media_id));
	}
	
}


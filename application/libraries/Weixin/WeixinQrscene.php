<?php



/**
* 二维码处理类
* 1. getQrcodeUrl: 根据场景ID创建二维码URL;
**/
class WeixinQrscene {

	public function __construct() {
		$CI =& get_instance();
		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');
	}
	
	// 1. 创建二维码，创建成功后，返回换取二维码的URL:
	// 如果需要创建永久二维码，则把$expire字段设置为空, 
	// 如果永久二维码是字符串类型的，直接把字符串传入$sceneId就可以了;
	public function getQrcodeUrl($sceneId, $expire=NULL){
		if($expire != null){
			// 临时二维码
			//WeixinKefu::sendTextToAdmin('临时二维码');
			$data = array(
					'action_name' => 'QR_SCENE',
					'action_info' => array('scene' => array('scene_id' => (int)$sceneId)),
					'expire_seconds' => $expire,
				);
		}else{
			// 永久二维码分为两种：
			// 		1. 数字类型，scene_id只能是1－100000
			//		2. 字符串类型：scene_str长度为1-64
			if(preg_match("/^[0-9]*$/", $sceneId)){	// 数字类型
				//WeixinKefu::sendTextToAdmin('数字二维码');
				if($sceneId < 1 || $sceneId > 100000){
					$sceneId = 1;
				}
				$data = array(
						'action_name' => 'QR_SCENE',
						'action_info' => array('scene' => array('scene_id' => $sceneId))
					);
			}else{		// 字符串类型，一般都创建这种；
				//WeixinKefu::sendTextToAdmin('字符串二维码');
				$data = array(
						'action_name' => 'QR_LIMIT_STR_SCENE',
						'action_info' => array('scene' => array('scene_str' => $sceneId))
					);
			}
		}
		// 知道生成什么类型的二维码后，开始请求：
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".wechat_token();
		$content = curl_post($url, json_encode($data));
//		pushlog('qrcode result: ', $content);
		$ret = json_decode($content, true);
		if(is_array($ret) && array_key_exists('ticket', $ret)) {
			$qr_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $ret['ticket'];
			return $qr_url;
		}
		return '';
	}
	
	// 2. 根据二维码的ticket换取二维码
	public static function getQrpicByTicket($ticket){

	}
		
  }


<?php

/**
* 素材管理类，用于管理微信公众号素材
**/
class WeixinMaterial {

	public function __construct() {
		$CI =& get_instance();
		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');
	}
    
	// 1. 获取永久素材列表： type=image/video/voice/news
	public static function getMaterialList($type, $offset=0, $count=1000){
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".wechat_token();
		$json = json_encode(
			array(
				'type' => $type,
				'offset' => $offset,
				'count' => $count
			), JSON_UNESCAPED_UNICODE);
		$ret = curl_post($url, $json);
		return $ret;
	}
	
  }
?>
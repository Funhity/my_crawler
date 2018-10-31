<?php

/**
* 素材管理类，用于管理微信公众号素材
**/

require_once 'WeixinBase.php';

class WeixinMaterial extends WeixinBase {

	public function __construct($params) {
		parent::__construct($params);
	}
    
	// 1. 获取永久素材列表： type=image/video/voice/news
	public function getMaterialList($type, $offset=0, $count=1000){
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->accessToken;
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
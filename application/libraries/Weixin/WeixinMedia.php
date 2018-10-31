<?php
  
/**
* 多媒体类: 上传永久/临时 素材
**/
class WeixinMedia {

	public function __construct() {
		$CI =& get_instance();
		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');
	}

	// 1. 上传临时素材: 上传成功后，返回Media ID:, 临时素材有效期为3天
	// 返回JSON： {"type":"image","media_id":"<media_id>","created_at":1445908237}
	public function uploadTempMedia($filePath, $type='image'){
		$url = "https://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".wechat_token()."&type=".$type;
		$args['media'] = new \CurlFile("$filePath", 'image/jpg', '@'."$filePath");
		$ret = curl_post($url, $args);
		return $ret;
	}
	
	// 上传永久素材（最多5000个）
	public function uploadPermanentMaterial($filePath, $type='image'){
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".wechat_token();
		$args['media'] = new \CurlFile("$filePath", 'image/jpg', '@'."$filePath");
		$args['type'] = $type;
		$ret = curl_post($url, $args);
		return $ret;
	}
	
}
?>
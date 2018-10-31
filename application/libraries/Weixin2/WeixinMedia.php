<?php
  
/**
* 多媒体类: 上传永久/临时 素材
**/

require_once 'WeixinBase.php';


class WeixinMedia extends WeixinBase {

	public function __construct($params) {
		parent::__construct($params);
	}

	// 1. 上传临时素材: 上传成功后，返回Media ID:, 临时素材有效期为3天
	// 返回JSON： {"type":"image","media_id":"<media_id>","created_at":1445908237}
	public function uploadTempMedia($filePath, $type='image'){
		$url = "https://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->accessToken."&type=".$type;
		$args['media'] = new \CurlFile("$filePath", 'image/jpg', '@'."$filePath");
		$ret = curl_post($url, $args);
		return $ret;
	}
	
	// 上传永久素材（最多5000个）
	public function uploadPermanentMaterial($filePath, $type='image'){
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$this->accessToken;
		$args['media'] = new \CurlFile("$filePath", 'image/jpg', '@'."$filePath");
		$args['type'] = $type;
		$ret = curl_post($url, $args);
		return $ret;
	}


	/**
	 * 通过传入一个图片文件Url地址，把图片上传到微信服务器，然后返回一个media_id
	 * @param $url
	 * @return string|null
	 */
	public function uploadImageMediaByRemoteUrl($url) {
		// 下载文件内容到本地:
		$fileContent = curl_get($url);
		pushlog('下载合成的图片到本地: ', $url);
		$tmpFile = APPPATH.'tmp/'.md5(uniqid("", true)).'.jpg';
		$handle = fopen($tmpFile, "w");
		fwrite($handle, $fileContent);
		fclose($handle);
		pushlog('文件写入本地成功: ', $tmpFile);
		$upMediaRetJson = $this->uploadTempMedia($tmpFile);

		$upMediaRet = json_decode($upMediaRetJson, true);
		if(!is_array($upMediaRet) || array_key_exists('errcode', $upMediaRet)) {
			pushlog('组合图片上传微信出现错误', $upMediaRet, 5);
			return null;
		}
		unlink($tmpFile);           // 把临时文件删掉；
		return $upMediaRet['media_id'];
	}
	
}
?>
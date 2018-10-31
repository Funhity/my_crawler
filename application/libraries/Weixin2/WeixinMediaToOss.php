<?php
  
/**
* 多媒体类: 上传微信媒体文件到oss
**/

require_once APPPATH.'vendor/autoload.php';

require_once 'WeixinBase.php';


class WeixinMediaToOss extends WeixinBase {

	protected $CI;

	public function __construct($params) {
		parent::__construct($params);
		$this->CI = & get_instance();
	}

	/**
	 * 根据media_id下载语音消息，然后转存到oss
	 * @param $media_id
	 * @return null|string
	 */
	public function restoreWechatMediaToOss($media_id) {

		$this->CI->load->helper('curl_get');
		$this->CI->load->helper('oss_newkey');

		// 下载微信文件:
		$fileUrl = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->accessToken."&media_id=".$media_id;
		$fileContent = curl_get($fileUrl);

		if(strlen($fileContent) < 1000) {
			pushlog('下载微信文件失败: ', $fileUrl);
			return null;
		}
		pushlog('获取到媒体内容, url: ', $fileUrl);

		$file = APPPATH . 'tmp/' . date('YmdHis') . '__' . md5(uniqid("", true)) . '.amr';
		file_put_contents($file, $fileContent);

		$file2 = $file . ".mp3";
		pushlog('临时文件: ', $file);
		shell_exec("/usr/bin/ffmpeg -i ".$file." -ab 128k -f mp3 ".$file2);

		// 把语音文件上传到阿里云
		$ossKey = oss_newkey('english').'.mp3';
		$ossCfg = Consts::OSS_CFG;
		$ossClient = new \OSS\OssClient($ossCfg['id'], $ossCfg['key'], $ossCfg['host']);
		try {
			$ossClient->uploadFile($ossCfg['bucket'], $ossKey, $file2);
//			$ossClient->putObject($ossCfg['bucket'], $ossKey, $fileContent);
		} catch (\OSS\Core\OssException $e) {
			pushlog("文件上传oss出现错误: \n" . print_r($e->getMessage(), true) . "\n  文件key: " . $ossKey, 5);
			return null;
		}
		$cdnUrl = Consts::OSS_CDN_URL.'/'.$ossKey;
		pushlog('oss上传成功: ', $cdnUrl);
		// 把临时文件删掉
		unlink($file);
		unlink($file2);
		return $cdnUrl;
	}
}
?>
<?php
/**
 * OSS文件上传辅助类
 * 通过传入一个远程文件Url，实现oss上传，上传完成后返回cdn url;
 * 必须要传入一个key，方法内部不处理key的生成
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'vendor/autoload.php';

if ( ! function_exists('ossurlupload'))
{

	function ossurlupload($url, $key){

		$CI =& get_instance();
		$CI->load->helper('curl_get');

		$fileContent = curl_get($url);

		$ossCfg = Consts::OSS_CFG;
		$ossClient = new \OSS\OssClient($ossCfg['id'], $ossCfg['key'], $ossCfg['host']);

		try {
				$ossClient->putObject($ossCfg['bucket'], $key, $fileContent);		// 这里file是文件内容;

		} catch (\OSS\Core\OssException $e) {
			pushlog('文件上传oss出现错误', $e->getMessage(), 5);
			return null;
		}
		return Consts::OSS_CDN_URL.'/'.$key;
	}
}

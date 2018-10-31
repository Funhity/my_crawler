<?php
/**
 * OSS文件上传辅助类
 * 该方法只适用于少量的文件上传，大量循环的上传会导致创建大量的OSS对象，最终导致php内存分配失败
 * 因此多文件上传，不建议调用该方法;
 * method 有两种方式: method=upload, $file传入文件路径; method=put, $file传入文件内容对象;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'vendor/autoload.php';

if ( ! function_exists('ossupload'))
{

	function ossupload($file, $module, $fileExtension='jpg', $method="upload"){

		$CI =& get_instance();

		$CI->load->helper('oss_newkey');

		$ossCfg = Consts::OSS_CFG;
		$ossClient = new \OSS\OssClient($ossCfg['id'], $ossCfg['key'], $ossCfg['host']);

		// ['quest', 'live', 'rss', 'user', 'tmp', 'common', 'avatar'];
		$ossKey = oss_newkey($module).'.'.$fileExtension;
		try {
			if($method == 'put') {
				$ossClient->putObject($ossCfg['bucket'], $ossKey, $file);		// 这里file是文件内容;
			} else {
				$ossClient->uploadFile($ossCfg['bucket'], $ossKey, $file);		// 这里file是一个路径;
			}

		} catch (\OSS\Core\OssException $e) {
			pushlog('文件上传oss出现错误', $e->getMessage(), 5);
			return null;
		}
		return Consts::OSS_CDN_URL.'/'.$ossKey;
	}
}

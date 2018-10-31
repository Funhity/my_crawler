<?php
/**
 * S3文件上传辅助类
 * 该方法只适用于少量的文件上传，大量循环的上传会导致创建大量的s3对象，最终导致php内存分配失败
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'vendor/autoload.php';

if ( ! function_exists('s3upload'))
{

	function s3upload(&$file, $module, $fileExtension='jpg', $contentType='image/jpeg'){

		$CI =& get_instance();

		$CI->load->helper('s3_newkey');
		$s3 = new \Aws\S3\S3Client(Consts::S3_CFG);

		if($fileExtension == 'mp3') {
			$contentType = 'audio/mp3';
		}

		// ['quest', 'live', 'rss', 'user', 'tmp', 'common', 'avatar'];
		$s3Key = s3_newkey($module).'.'.$fileExtension;
		$cdnPath = null;
		try {
			$s3->putObject([
				'Bucket'        =>  Consts::S3_BUCKET,
				'Key'           =>  $s3Key,
				'Body'          =>  $file,
				'ContentType'   =>  $contentType,
			]);
			$cdnPath = Consts::S3_CDN_URL.substr($s3Key, 4);
		} catch (\Aws\S3\Exception\S3Exception $e) {
			return null;
		}
		return $cdnPath;
	}
}

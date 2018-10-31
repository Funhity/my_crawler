<?php
/**
 * S3存储开放部分key生成器
 *		根据key的类型分别生成不同类型的key，
 * 		按业务模块来分，后面就是以天为日期的文件夹，后面就是文件名，此生成器生成的key不包含文件后缀。
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('oss_newkey'))
{

	function oss_newkey($module){

		$key = $module."/".date('Ymd')."/".md5(uniqid("", true));
		return $key;
	}
}

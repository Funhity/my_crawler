<?php
/**
 * 云存储资源切换到阿里云(默认是在aws上)
 * 目前只切换WonderGlobal上的s3开放资源（暂没有私有资源）
 * https://d3qiassb671423.cloudfront.net
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('static_switch_to_alicdn'))
{

	function static_switch_to_alicdn($src_url, $options=null){
		if(IN_CHINA == 1 && strpos($src_url, 'd3qiassb671423.cloudfront.net') !== false) {
			$src_url = str_replace('https://', 'http://', $src_url);		// 去掉https协议
			$src_url = str_replace('http://d3qiassb671423.cloudfront.net', 'http://static.dolphinlive.net', $src_url);
			if($options == 'avatar_200') {
				$src_url = $src_url . '?x-oss-process=style/avatar_200';
			} else if($options == 'avatar_600') {
				$src_url = $src_url . '?x-oss-process=style/avatar_600';
			}
		}
		return $src_url;
	}
}

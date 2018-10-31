<?php
/**
 * 使用curl发送post请求函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('curl_post_form'))
{

	function curl_post_form($url, $data=null, $headerArray=null){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if(is_array($data)) {
			$content = http_build_query($data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(is_array($headerArray)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
		}
		if(!curl_exec($ch)){
			error_log(curl_error($ch));
			$data = '';
		}else{
			$data = curl_multi_getcontent($ch);
		}
		curl_close($ch);
		return $data;
	}
}

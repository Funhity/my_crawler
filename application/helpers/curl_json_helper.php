<?php
/**
 * 以JSON格式发送POST请求到服务器
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('curl_json'))
{

	function curl_json($url, $arrayData){
		$data = json_encode($arrayData);//转换为JSON格式的字符串
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER,
			array('Content-Type:application/json',
				'Content-Length: ' . strlen($data))
		);
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

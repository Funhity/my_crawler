<?php
/**
 * 格式化方法/函数返回的数据格式:
 * c: 错误码;
 * m: 错误消息;
 * d: 返回的数据;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('resp_return'))
{

	function resp_return($error_code=0, $error_msg='', $data=null){

		$data = array(
			'c'		=>	$error_code,
			'm'		=>	$error_msg,
			'd'		=>	$data
		);
		return $data;
	}
}

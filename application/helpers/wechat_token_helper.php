<?php
/**
 * 获取微信token, 执行成功返回token字符串，执行失败返回空字符串;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('wechat_token'))
{

	function wechat_token($name='MP1'){

		$redis = RedisInstance::getInstance();
		$key = ENVIRONMENT.':WECHAT:'.$name.':access_token';
		$token = $redis->get($key);
		return $token == false ? '' : $token;
	}
}

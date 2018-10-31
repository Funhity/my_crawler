<?php
/**
 * Redis序列生成器
 * 每一个小时重置序列的值;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('redis_sequence'))
{

	function redis_sequence($date=false){

		$key = ENVIRONMENT.":TOOLS:INCR_SEQUENCE:".date('YmdH');

		$redis = RedisInstance::getInstance();
		// 判断是否存在，然后决定设置生存时间，这里并不担心重入的问题，因为每次incr都会+1, 而这里主要是设置key的生存期
		$exist = $redis->exists($key);
		if($exist == 0) {
			$redis->incr($key);			// 目的是生成一个key
			$redis->expire($key, 604800);		// key有效期为一个月
		}
		if($date == true) {
			return date('YmdHis') . $redis->incr($key);
		}
		return $redis->incr($key);
	}
}

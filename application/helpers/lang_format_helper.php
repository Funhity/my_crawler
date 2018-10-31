<?php
/**
 * 执行语言变量的替换;
 * 通过识别 #var# 来替换
 * 不传入变量不进行替换;
 * 传入的变量可以是
 * 		字符串：执行全部识别替换;
 * 		数组：按顺序执行识别替换;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('lang_format'))
{

	function lang_format($key, $default='', $replace=null){

		$CI =& get_instance();
		$langStr = $CI->lang->line($key, false);

		// 如果语言不存在，那么设置成传入的默认字符串
		if(empty($langStr)) {
			$langStr = $default;
		}

		// 没有传入替换变量，直接返回获取的语言
		if($replace == null) {
			return $langStr;
		}

		// 传入一个替换的变量，返回全部替换后的结果.
		if(is_string($replace) || is_numeric($replace)) {
			return str_replace('#var#', $replace, $langStr);
		}

		// 传入一个数组，按数组顺序每次替换一个位置:
		if(is_array($replace)) {
			foreach($replace as $item) {
				$from = '/'.preg_quote('#var#', '/').'/';
				$langStr = preg_replace($from, $item, $langStr, 1);
			}
		}
		return $langStr;
	}
}

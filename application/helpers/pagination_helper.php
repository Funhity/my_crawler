<?php
/**
 * 分页处理函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('pagination'))
{

	function pagination($index, $page_size, $all_count, $list){

		$page_size = $page_size < 1 ? 1: $page_size;

		$data['all_count'] = (int) $all_count;
		$data['page_size'] = (int) $page_size;

		$data['cur_page'] =  (int) (($index/$page_size)+1);
		$data['all_page'] = ceil($all_count/$page_size);
		$data['cur_idx'] = $index;
		$data['list'] = $list;
		return $data;
	}
}

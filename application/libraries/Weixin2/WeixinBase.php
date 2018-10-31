<?php

/**
* 客服类，用于向微信关注用户发送消息， 客户消息在用户操作过公众号48小时内有效；
**/
class WeixinBase {

	protected $CI = null;
	protected $accessToken = null;
	protected $params = null;

	public function __construct($params) {
		$this->CI =& get_instance();
		$this->CI->load->helper('wechat_token');
		$this->CI->load->helper('curl_get');
		$this->CI->load->helper('curl_post');

		$this->params = $params;
		$this->accessToken = wechat_token($params['weCfg']['name']);		// 这个name定义redis的key
	}
}


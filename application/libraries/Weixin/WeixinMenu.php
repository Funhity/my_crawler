<?php

  /**
  * 自定义菜单管理
  * 1. createMenu: 创建菜单请求,只能内部调用;
  * 2. setMenu: 创建菜单调用接口;
  * 3. getMenu: 获取自定义菜单列表;
  * 4. delMenu: 删除自定义菜单;
  **/
class WeixinMenu {

	public function __construct() {
		$CI =& get_instance();
		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');
	}

	// 1. 向微信服务器请求创建菜单的方法,只能内部调用：
	private function createMenu($menu){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".wechat_token();
		$content = curl_post($url, $menu);
		return $content;
	}
	
	// 2. 创建自定义菜单的方法，用于外部调用：
	public function renewMenu(){
		$this->delMenu();
		$menu = '{"button":[
				{
				  "type":"view",
				  "name":"Live",
				  "url":"http://web.dolphinlive.net/h5/wd_live/api_index"
				},
			]
		}';
		$ret = $this->createMenu($menu);
		return $ret;
	}
	
	// 3. 获取自定义菜单列表：
	public function getMenu(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".wechat_token();
		$content = curl_get($url);
		return $content;	
	}
	
	// 4. 删除自定义菜单：
	public function delMenu(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".wechat_token();
		$content = curl_get($url);
		return $content;	
	}
}
?>
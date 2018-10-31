<?php

  /**
  * 自定义菜单管理
  * 1. createMenu: 创建菜单请求,只能内部调用;
  * 2. setMenu: 创建菜单调用接口;
  * 3. getMenu: 获取自定义菜单列表;
  * 4. delMenu: 删除自定义菜单;
  **/
include_once "WeixinBase.php";
class WeixinMenu extends WeixinBase {

	public function __construct($params) {
		parent::__construct($params);
	}

	// 1. 向微信服务器请求创建菜单的方法,只能内部调用：
	private function createMenu($menu){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->accessToken;
		$content = curl_post($url, $menu);
		return $content;
	}
	
	// 2. 创建自定义菜单的方法，用于外部调用：
	public function renewMenu(){
		$this->delMenu();
		$menu = null;
		switch($this->params['weCfg']['name']) {
			case 'MP1':
				$menu = $this->getMp1Menu();
				break;
			case 'BM1':
				$menu = $this->getBM1Menu();
				break;
			case 'BMYY':
				$menu = $this->getBMYYMenu();
				break;
		}
		if($menu == null) {
			pushlog('更新公众号菜单时未找到公众号菜单配置, weCfg: ', $this->params['weCfg']);
			return false;
		}
		$ret = $this->createMenu($menu);
		return $ret;
	}
	
	// 3. 获取自定义菜单列表：
	public function getMenu(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->accessToken;
		$content = curl_get($url);
		return $content;	
	}
	
	// 4. 删除自定义菜单：
	public function delMenu(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->accessToken;
		$content = curl_get($url);
		return $content;	
	}


	// Wonder Live公众号菜单定义
	private function getMp1Menu() {
		$menu = '{"button":[
				{
				  "type":"view",
				  "name":"Live直播",
				  "url":"http://web.dolphinlive.mobi/wd_live2/live_list.html"
				},
			]
		}';
		return $menu;
	}


	// 斑马英语菜单:
	private function getBMYYMenu() {
		$menu = '{"button":[
				{
				  "type":"view",
				  "name":"进入 · 斑马英语",
				  "url":"http://web.dolphinlive.mobi/zebra_read/#/"
				},
			]
		}';
		return $menu;
	}

	private function getBM1Menu() {
		$menu = '{"button":[
				{
				   "type":"click",
					"name":"口语21天计划",
					"key":"MENU_CLICK_REGISTER_SPEAK"
			   },
			   {
				   "name":"课程介绍",
				   "type":"view",
				   "url":"https://web.zuiqiangyingyu.cn/zebra_app/activity_2002.html?activity_id=2002&qr_id=qr_21spk_2002_chn0"
			   },
			   {
				   "name":"付费通道",
				   "type":"view",
				   "url":"https://web.zuiqiangyingyu.cn/zebra_app/pay.html?activity_id=2002"
			   }
			]
		}';
		return $menu;
	}


}
?>
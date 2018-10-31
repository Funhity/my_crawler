<?php

namespace Mylib\Weixin;
use Mylib\Tools\CurlTool;

/**
* 该类用于连接微信服务器做OAuth2授权：
**/
class WeixinOauth {
    
	// 1. 授权第一步：使用从微信服务器发过来的code换取授权Token:
	public static function getAuthToken($code){
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WX_APPID').'&secret='.C('WX_APPKEY').'&code='.$code.'&grant_type=authorization_code';
		$content = CurlTool::curlGet($url);
		return $content;
	}
	
	// 2. 使用refresh_token延长授权时间（微信服务器在回复Token时会带有另一个用于更新的Token）
	public static function refreshAuthToken($refresh_token){
		$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".C('WX_APPID').'&secret='.C('WX_APP_KEY')."&grant_type=refresh_token&refresh_token=".$refresh_token;
		$content = CurlTool::curlGet($url);
		return $content;
	}
	
	// 3. 通过Token来向微信服务器获取用户信息：
	public static function getUserInfoByOAuth($access_token, $openid){
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token.'&openid='.$openid.'&lang=zh_CN';
		$content = CurlTool::curlGet($url);
		return $content;
	}
}


?>
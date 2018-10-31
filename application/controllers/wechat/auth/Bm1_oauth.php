<?php

defined('BASEPATH') OR exit('No direct script access allowed');

 /**
  * @api {post} http://api.dolphinlive.mobi/index.php/wechat/auth/Bm1_oauth 01. Bm1【斑马招生办】 Web授权
  * @apiName wechat_auth_bm1_oauth
  * @apiVersion 1.0.0
  * @apiGroup WechatBm
  *
  * @apiDescription 斑马英语BM1公众号授权<br />
  * <code>如果授权的类型为snapi_base, 并且用户的微信账号没有在系统中注册过，那么授权登陆失败，这点要注意</code>
  * @apiParam {String} web_url 授权完成后需要返回的页面，地址后缀至少有一个参数(如?a=1)（一般是返回本页面，所以可以填本页面的地址）
  * @apiParam {String="snsapi_base","snsapi_userinfo"} [scope="snsapi_base"], 授权是否需要获取用户信息
  *
  * @apiSuccess (成功返回url参数) {String} token 后台api接口的token <br />
  *       页面拿到token后，把token写入localStorage或cookie，然后页面replace到没有token的本页面，
  *       后面就拿这个token与api接口进行交互 ;
  *
  * @apiSuccess (失败返回url参数) {String} error_code 授权失败后的返回码<br />
  *                           <code>100</code>=用户未在系统注册;
  * @apiSuccess (失败返回url参数) {String} error_msg 授权失败后的返加错误信息;
  *
  */

class Bm1_oauth extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        $web_url = $this->getParam2('web_url');
        $scope = $this->getParam2('scope');

        if($scope != 'snsapi_userinfo') {
            $scope = 'snsapi_base';
        }
        pushlog('web_url: ' . $web_url . ', scope: ' . $scope, '');

        $weCfg = Consts::WECHAT_BM1;

        $this->load->library('session');        // 加载session
        $_SESSION['weoauth_web_url'] = $web_url;
        
        //$_SERVER['REQUEST_SCHEME'] . 
        $oauthBack = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] . '_back';
        pushlog('oauth', $oauthBack);
        
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $weCfg['appid']
            . "&redirect_uri=" .
            urlencode($oauthBack)
            . "&response_type=code&scope=".$scope."&state=1#wechat_redirect";

        header("Location: $url");
        exit();
    }

    protected function isCheckToken() {
        return false;
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @api {cli} wechat/cli/wechat_user_sync 04. 同步微信用户信息
 * @apiName wechat_cli_wechat_user_sync
 * @apiVersion 1.0.0
 * @apiGroup WechatMp
 *
 * @apiDescription 同步当前已关注公众号的用户信息到系统，如果用户没有在系统注册，为用户注册成系统用户;<br />
 * 该脚本会遍历微信服务器上的已关注用户列表，如果发现本地系统没有这个用户，则执行注册成本地用户;<br />
 * 这个脚本只是用于实现用户存在差异的情况下进行同步，一般情况下不需要去执行。<br />
 */
class Wechat_user_sync extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        if(!is_cli()) $this->respError('code_3000001', '只能从命令行访问');

        // 从微信接口获取公众号所有用户的openid列表:
        $next_openid = '';
        $openidCount = 0;

        $this->load->library('Weixin/WeixinUser');
        $this->loadModel('user/wechat_user');
        while(true) {
            $retJson = $this->weixinuser->getUserList($next_openid);
            $ret = json_decode($retJson, true);
            if(!is_array($ret) || array_key_exists('errcode', $ret)){
                exit('wechat user list api error');
            }
            echo $retJson . "\n\n";
            $openidCount = $openidCount + count($ret['data']['openid']);

            foreach($ret['data']['openid'] as $openid){
                echo "OpenId: " . $openid . "\n\n";
                $this->wechat_user->mpSubscribeCheck($openid, 1);
            }
            if($ret['next_openid'] == ''){
                break;		// 退出循环
            }else{
                $next_openid = $ret['next_openid'];
            }
            echo PHP_EOL . '------------------------------- 循环分割线 -------------------------------' . PHP_EOL;
        }
        echo '用户信息检查完成，公众号用户数: ', $openidCount;
    }

    protected function isCheckToken() {
        return false;
    }
}

?>

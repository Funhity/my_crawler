<?php

defined('BASEPATH') OR exit('No direct script access allowed');


// 【斑马英语】公众号 BMYY 授权回调处理

class Bmyy_oauth_back extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        $code = $this->getParam2('code');

        //加载session
        $this->load->library('session');
        $this->load->helper('curl_post_form');

        $postHeader = array('Content-type: application/x-www-form-urlencoded');
        $postData = array(
            'login_type'    =>  102,
            'system'        =>  'web',
            'open_token'    =>  $code,
        );
        $postRetJson = curl_post_form("http://" . Consts::API_ADDR . '/index.php/api/user/Login', $postData, $postHeader);
        $postRet = json_decode($postRetJson, true);

        pushlog('wechat user login using code result: ', $postRet);

        $backUrl = $_SESSION['weoauth_web_url'];
        if(strpos($backUrl, '?') == false) $backUrl = $backUrl . '?tok12=1';
        if($postRet['c'] == 0) {
            $backUrl = $backUrl.'&token='.$postRet['d']['token'].'&timestamp='.time();
            pushlog('login ok, backUrl: ', $backUrl);
            header("Location: $backUrl"); // 返回到调用前端页面，并把token传递过去
            exit();
        }
        $backUrl = $backUrl.'&error_code='.$postRet['d'].'&error_msg='.$postRet['m'];
        pushlog('oauth error, backurl:', $backUrl);
        header("Location: $backUrl"); // 返回到调用前端页面，并把错误信息传递过去
        exit();
    }

    protected function isCheckToken() {
        return false;
    }

}

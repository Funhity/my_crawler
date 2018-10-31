<?php

defined('BASEPATH') OR exit('No direct script access allowed');


// 【斑马招生办】公众号1 BM1 授权回调处理

class Bm1_oauth_back extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        pushlog('wechat callback', 'wechat callback');
        $code = $this->getParam2('code');
        $this->load->helper('curl_get');
        //加载session
        $this->load->library('session');
        $this->load->helper('curl_post_form');

        $postHeader = array('Content-type: application/x-www-form-urlencoded');
        $postData = array(
            'login_type'    =>  101,
            'system'        =>  'web',
            'open_token'    =>  $code,
        );
        wlog($code);

        // $postRetJson = curl_post_form("http://" . Consts::API_ADDR . '/index.php/api/user/Login', $postData, $postHeader);
        // pushlog('postRetJson', $postRetJson);
        $url = "http://".Consts::API_ADDR."/index.php/api/user/Login?login_type=101&system=web&open_token=".$code;
        $postRetJson = file_get_contents($url);
        for ($i = 0; $i <= 31; ++$i) { 
            $postRetJson = str_replace(chr($i), "", $postRetJson); 
        }
        $postRetJson = str_replace(chr(127), "", $postRetJson);
        if (0 === strpos(bin2hex($postRetJson), 'efbbbf')) {
           $postRetJson = substr($postRetJson, 3);
        }
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

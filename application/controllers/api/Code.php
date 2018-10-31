<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * 关于返回码的描述
 */
class Code extends MY_Controller {
    
    
    /**
     * 显示所有的返回码
     */
    protected function showAllCode() {
        $this->echoCode(Respcode::CODE_OK, '操作成功');
        $this->echoCode(Respcode::CODE_ERROR_NETWORK, '网络异常，请稍后再试');
        $this->echoCode(Respcode::CODE_ERROR_EMPTY_PARAM, '参数为空');
        $this->echoCode(Respcode::CODE_ERROR_REQ_TOO_MUCH, '请求过于频繁');
        $this->echoCode(Respcode::CODE_ERROR_OUTSIDE, '外部第三方系统返回的错误');
        $this->echoCode(Respcode::CODE_ERROR_TOKEN, 'token已过期，请重新登录');
        $this->echoCode(Respcode::CODE_ERROR_USER_NOT_FOUND, '找不到用户');
        $this->echoCode(Respcode::CODE_ERROR_FORMATE, '参数不符合要求');
        $this->echoCode(Respcode::CODE_ERROR_OPTION, '错误的操作，不允许的操作');
        $this->echoCode(Respcode::CODE_ERROR_RES_NOT_FOUND, '找不到请求的资源');
        $this->echoCode(Respcode::CODE_ERROR_SYS_ERROR, '服务器环境错误');
        $this->echoCode(Respcode::CODE_ERROR_ODER_HAS_PAID, '订单已经支付过了');
    }
    
    
    protected function processForUser($isLogin, $me, $token_type) {
        echo '<center><h1>返回码说明</h1>';
        echo '<table border=3px width=60%>';
        echo '<tr><th width=100px>返回码</th><th>说明</th><tr>';
        $this->showAllCode();
        echo '</table></center>';
    }
    
    
    
    
    
    private function echoCode($code, $detail) {
        echo '<tr><td>'.$code.'</td><td>'.$detail.'</td></tr>';
    } 
    
    
    
    
    
    protected function isCheckToken() {
        return false;
    }
    
    
    
    
}


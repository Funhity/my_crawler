<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 查询某个用户所有有关的客服消息列表, 该用户发送的消息，及客服人员回复的消息列表;
 * token使用海升的微信号的token:
 * 这里的参数是通过表单方式提交上来的;
 * 传入参数:
 *          kf_token;
 *          appid;
 *          session_openid;
 */

class Query_kefu_msg_list extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        $kf_token = $this->getParam2('kf_token');
        $app = $this->getParam2('app');
        $session_openid = $this->getParam2('session_openid');

        if(empty($kf_token) || $kf_token != Consts::KF_TOKEN) {
            $this->respErrorParam('invalid visit');
        }

        // 检查是否存在微信应用配置: 映射出应用的配置信息:
        $appCfg = constant('Consts::'.strtoupper($app));
        if(empty($appCfg)) {
            pushlog('未找到appCfg配置信息', $appCfg, 3);
            $this->respErrorSystem('未找到应用名');
        }
        pushlog("当前执行的应用名: " . $app . ", appid: " . $appCfg['appid'], '');

        // 获得消息列表: 最多返回最近的100条消息:
        $sql = "select * from t_kefu_msg where appid = ? and session_openid = ? order by datetime desc limit 100;";
        $query = $this->db->query($sql, [$appCfg['appid'], $session_openid]);
        $msgList = $query->result_array();

        // 对数据进行逆序:
        $resortedList = [];
        foreach($msgList as $msg) {
            array_unshift($resortedList, $msg);
        }

        $this->respOk($resortedList);
    }

    protected function isCheckToken() {
        return false;
    }
    
    
}

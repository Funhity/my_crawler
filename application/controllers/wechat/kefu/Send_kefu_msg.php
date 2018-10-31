<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 发送客服消息给用户:
 * 传入参数:
 *          kf_token;
 *          appid;
 *          session_openid;
 *          content;
 */

class Send_kefu_msg extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        $kf_token = $this->getParam2('kf_token');
        $app = $this->getParam2('app');
        $session_openid = $this->getParam2('session_openid');
        $content = trim($this->getParam2('content'));


        // 检查token是否有效:
        if(empty($kf_token) || $kf_token != Consts::KF_TOKEN) {
            pushlog('发送客服消息非法访问，token错误', $kf_token, 4);
            $this->respErrorToken('invalid visit');
        }

        // 检查是否存在微信应用: 映射出微信应用的配置信息:
        $appCfg = constant('Consts::'.strtoupper($app));
        if(empty($appCfg)) {
            pushlog('未找到appCfg配置信息', $appCfg, 3);
            $this->respErrorSystem('未找到应用名');
        }
        pushlog("当前执行的应用名: " . $app . ", appid: " . $appCfg['appid'], '');
        $this->load->library('Weixin2/WeixinKefu', array('weCfg' => $appCfg));

        // 检查内容是否为空:
        if(empty($content)) {
            pushlog('客服接口发送内容为空', $content, 3);
            $this->respErrorSystem('发送内容为空');
        }

        // 检查session_openid是否存在:
        $sql = "select * from t_kefu_msg where appid = ? and session_openid = ? limit 1;";
        $query = $this->db->query($sql, [$appCfg['appid'], $session_openid]);
        $msg = $query->row_array();
        if(empty($msg)) {
            $this->respErrorResourceNotFound('session_openid');
        }

        // 把客服消息通过客服接口发送给用户:
        $ret = json_decode($this->weixinkefu->sendText($session_openid, $content), true);

        if($ret['errcode'] == 0) {
            // 添加客服消息到数据库:
            $newData = array(
                'appid'         =>  $appCfg['appid'],
                'app_name_cn'   =>  $appCfg['name_cn'],
                'msgid'         =>  md5(uniqid("", true)),
                'fromtype'      =>  2,                                  // 消息的来源类型: 1=客户发送消息; 2=客服回复消息
                'msgtype'       =>  'text',                             // 消息的媒体类型: 文字/图片
                'content'       =>  $content,
                'openid'        =>  'kefu_user1',
                'session_openid'=>  $session_openid,
                'nickname'      =>  '客服',
                'avatarurl'     =>  'https://static.zuiqiangyingyu.cn/manual/logo/'.$appCfg['name'].'.png',
                'datetime'      =>  time(),
                'datetime_str'  =>  date('Y-m-d H:i:s'),
            );
            $this->db->insert('t_kefu_msg', $newData);
            if($this->db->affected_rows() != 1) {
                pushlog('发送客服消息失败: ', $newData, 3);
                $this->respError('102', '消息发送成功，数据库保存失败');
            }
            pushlog('保存客服消息成功', $this->db->insert_id());

            $this->respOk();
        } else if($ret['errcode'] == 45047) {
            pushlog('发送客服消息失败: ', $ret, 3);
            $this->respError('102', '客服会话超时，不能再发送');
        }
        $this->respError('101', '客服接口发送失败', $ret);
    }

    protected function isCheckToken() {
        return false;
    }
    
    
}

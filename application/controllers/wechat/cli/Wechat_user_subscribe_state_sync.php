<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @api {cli} wechat/cli/wechat_user_subscribe_state_sync 05. 同步用户的关注状态信息
 * @apiName wechat_cli_wechat_user_subscribe_state_sync
 * @apiVersion 1.0.0
 * @apiGroup WechatMp
 *
 * @apiDescription
 * 1. 更新数据库中当前公众号的wechat_user为未关注状态;<br />
 * 2. 从微信接口获得已关注用户的openid列表(分批获取，每次最多一千条)
 * 3. 最后把这个openid列表用户更新为已关注状态;
 * 4. 进入下一次循环，直到所有用户openid列表遍历结束;
 */
class Wechat_user_subscribe_state_sync extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        if(!is_cli()) $this->respError('code_3000001', '只能从命令行访问');

        $weCfg = Consts::WECHAT_BM1;


        // 从微信接口获取公众号所有用户的openid列表:
        $next_openid = '';
        $openidCount = 0;

        $this->load->library('Weixin2/WeixinUser', array('weCfg' => $weCfg));
        $this->loadModel('user/wechat_user');

        // 首先更新用户为未关注状态:
        $sql = "update t_wechat_user set is_subscribe = 0 where appid = ?;";
        $result = $this->db->query($sql, [$weCfg['appid']]);
        if(!$result) {
            exit("\n更新微信用户状态失败\n\n");
        }

        while(true) {
            $retJson = $this->weixinuser->getUserList($next_openid);
            $ret = json_decode($retJson, true);
            if(!is_array($ret) || array_key_exists('errcode', $ret)){
                exit('wechat user list api error: ' . $retJson);
            }
            echo $retJson . "\n\n";

            $openidStr = '';
            $openidList = $ret['data']['openid'];

            if(empty($openidList)) {
                exit("用户列表为空, 退出循环\n\n");
            }

            foreach($openidList as $openid) {
                $openidStr = $openidStr . "'" .$openid . "'" . ', ';
            }
            $openidStr = substr($openidStr, 0, strlen($openidStr)-2);

            $sql = "update t_wechat_user set is_subscribe = 1 where appid = '".$weCfg['appid']."' and openid in (".$openidStr.");";
            echo "SQL: " . $sql . "\n\n";
            $result = $this->db->query($sql, [$weCfg['appid']]);
            if(!$result) {
                exit("\n更新微信用户状态为已关注状态失败\n\n");
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

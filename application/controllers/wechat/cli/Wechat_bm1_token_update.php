<?php

defined('BASEPATH') OR exit('No direct script access allowed');

 /**
  * @api {cli} wechat/cli/wechat_bm1_token_update 06. 斑马招生办AccessToken更新
* @apiName wechat_cli_wechat_bm1_token_update
* @apiVersion 1.0.0
* @apiGroup WechatMp
*
  * @apiDescription 定期更新微信接口使用的access_token及jsapi_ticket信息<br />
  *   这个脚本只能在一个环境（正式环境）上跑，交叉更新会导致系统不稳定;<br />
  *   脚本基于Linux计划任务(root用户)每小时跑一遍: <br />
  *   <code>0 * * * * /opt/php56/bin/php /data/vg02_lv01/web/bm_wonder_server/webroot/index_cli.php wechat/cli/Wechat_bm1_token_update >> /data/vg02_lv01/web/bm_wonder_server/application/logs/crond_wechat_bm1_token_update.log</code><br />
  */
class Wechat_bm1_token_update extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        if(!is_cli()) $this->respError('code_3000001', '只能从命令行访问');

        $this->load->helper('curl_get');

        $weCfg = Consts::WECHAT_BM1;
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$weCfg['appid']."&secret=".$weCfg['secret'];
        $content = curl_get($url);
        $ret=json_decode($content,true);
        if(array_key_exists('errcode',$ret)&&$ret['errcode']!=0){
            echo "\nGet Token Failed: " . $content . "\n";
            exit();
        }
        echo "\n Token Result: " . $content . "\n";
        $newToken = $ret['access_token'];

        // 更新JsapiTicket:
        $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$newToken."&type=jsapi";
        $content = curl_get($url);
        $ret=json_decode($content,true);
        if(array_key_exists('errcode',$ret)&&$ret['errcode']!=0){
            echo "\nGet Jsapi Ticket Failed: " . $content . "\n";
            exit();
        }
        echo "\n Jsapi Result: " . $content . "\n";
        $newTicket = $ret['ticket'];
        $newUpdate = date('Y-m-d H:i:s');

        // 把数据更新到Redis中:
        $redis = RedisInstance::getInstance();
        $redis->set(ENVIRONMENT.':WECHAT:'.$weCfg['name'].':access_token', $newToken);
        $redis->set(ENVIRONMENT.':WECHAT:'.$weCfg['name'].':jsapi_ticket', $newTicket);
        $redis->set(ENVIRONMENT.':WECHAT:'.$weCfg['name'].':token_update', $newUpdate);
        echo "\nToken update success\n";
    }

    protected function isCheckToken() {
        return false;
    }

}

?>

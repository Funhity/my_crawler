<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @api {post} wechat/wechat_url_jsapi_sign 03. 公众号JSAPI签名接口
 * @apiName wechat_api_wechat_url_jsapi_sign
 * @apiVersion 1.0.0
 * @apiGroup WechatMp
 *
 * @apiDescription 生成页面生成jsapi签名信息<br />
 * @apiParam {String} [url] 可传入url对指定的地址进行签名，如果没有传入，对请求的页面地址进行签名
 * @apiParam {String} [mp_id=1] 公众号id, 传入这个id，执行不同的公众号jsapi签名<br />
 * <code>1</code>=MP1, Wonder知识共享公众号签名;<br />
 * <code>2</code>=BM1, 斑马招生办1公众号签名;<br />
 * <code>3</code>=BMYY 斑马英语公众号签名;<br />
 *
 * @apiUse CommonRespok
 * @apiSuccess (返回参数) {String} d.appid 应用id;
 * @apiSuccess (返回参数) {String} d.nonceStr 随机字符串
 * @apiSuccess (返回参数) {String} d.signature 签名信息
 * @apiSuccess (返回参数) {String} d.url 签名的url地址，这个信息不是jsapi配置必须的，仅仅是用于识别签名地址是否与当前地址是否一致。
 * @apiSuccessExample 执行成功后返回
 {
  "c": "0",
  "m": "MP1",   // 执行成功后这个字段会带上公众号名称的简写
  "d": {
    "appId": "wxe3e6f2e875c60a8d",
    "nonceStr": "dbb800ab996945cb85ae7c1798636585b0baab9f",
    "timestamp": 1492415212,
    "signature": "54e846bff902572b9af3827281008c85fd8f4de5",
    "url": "http://srv.wonder.local/index.php/wechat/Wechat_url_jsapi_sign"
  }
}
 *
 */


/**
 * 微信链接jsapi签名:
 */
class Wechat_url_jsapi_sign extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        // 获取需要签名的url
        $url = $this->getParam2('url');
        if(empty($url)) {
            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }

        $mp_id = $this->getParam2('mp_id');
        if(empty($mp_id)) {
            $mp_id = 1;
        }

        $weCfg = Consts::WECHAT_MP1;
        switch($mp_id) {
            case 2:
                // 斑马招生办1
                $weCfg = Consts::WECHAT_BM1;
                break;
            case 3:
                $weCfg = Consts::WECHAT_BMYY;
                break;
        }

        // 获取JSAPI Ticket：
        $redis = RedisInstance::getInstance();
        $jsapiTicket = $redis->get(ENVIRONMENT.':WECHAT:'.$weCfg['name'].':jsapi_ticket');
        $timestamp = time();
        $nonceStr = sha1($timestamp);
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $weCfg['appid'],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "signature" => $signature,
            "url"       => $url,
            // "rawString" => $string
        );
        $this->respOk($signPackage, $weCfg['name']);
    }

    protected function isCheckToken() {
        return false;
    }

}

?>

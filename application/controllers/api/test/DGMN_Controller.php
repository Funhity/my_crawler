<?php
/**
 * idiom 父类
 *
 * @since 20170926
 * @author pinheng
 */
defined('BASEPATH') or exit('No direct script access allowed');
// 引入父类
require dirname(__FILE__) . '/../Api_Parent_Controller.php';
require_once APPPATH . '/libraries/WeappEnc/WXBizDataCrypt.php';


class DGMN_Controller extends Api_Parent_Controller
{

    protected $offer_id = "1450018212";//米大师分配的offer_id
    protected $midas_sandbox_app_key = "3IkdeDQRe2yXkSgEkPr9fr6gQqVaVGL6";//沙箱AppKey
    protected $midas_app_key = "DtRtWwncsZZSHOtIxXR7nzJSoRZjS8iQ";// 现网AppKey


    /**
     * @var CI_DB_query_builder
     */
    public $idiom_db;
    public $redis;
    protected $_redis_config;
    public $redis_prefix;
    public $redis_access_token;
    private $sign_ticket = "FRl2LQfeWgyWtUxcXs9g0jvLGtWQUuAP";
    public $exprie_time = 90 * 24 * 60 * 60;

    //免费次数
    public $free_chance;

    public function __construct()
    {
        parent::__construct();
        $this->free_chance = 3;
        $this->_redis_config = $this->config->item('redis_idiom');
        $this->redis_prefix = 'da_guai_meng_niang_';
        $this->redis_access_token = ENVIRONMENT . ':da_guai_meng_niang:access_token';
        $this->idiom_db = $this->load->database('da_guai_meng_niang', true);
        $this->redis = RedisInstance::getInstance($this->_redis_config);
    }

    protected function processForUser($isLogin, $me, $token_type)
    {
    }

    function getWxCfg()
    {
        return Consts::WECHAT_DA_GUAI_MENG_NIANG;
    }


    /**
     * 小程序码（测试备用）
     *
     * @param [type] $path_url
     *            [description]
     * @return [type] [description]
     */
    final public function getWeappCode($param, $pathUrl, $width, $redis = '')
    {
        $this->load->helper('curl_post');
        $token = $this->getAccessToken();
        if ($token === false) {
            return false;
        }
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $token;

        $json = json_encode(array(
            'scene' => urlencode($param),
            'page' => $pathUrl,
            'width' => $width
        ), JSON_UNESCAPED_UNICODE);

        $response = curl_post($url, $json);
        if (json_decode($response) != null) {
            return false;
        }
        return $response;
    }

    /**
     * +----------------------------------------------------------
     * 获取小程序二维码
     * +----------------------------------------------------------
     */
    final public function getSQCode($path_url)
    {
        $this->load->helper('curl_post');
        $token = $this->getAccessToken();
        if ($token === false) {
            return false;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=" . $token;

        $json = json_encode(array(
            'path' => $path_url, // 扫码
            'width' => 280
        ), JSON_UNESCAPED_UNICODE);
        $textRet = curl_post($url, $json);
        return $textRet;
    }

    /**
     * @desc 检查登录
     * @param $uid
     * @return bool
     */
    final public function checkLogin($uid)
    {
        $token = $this->redis->get($this->redis_prefix . $uid);
        if ($token == false || empty($token)) {
            $this->respError('505', '请先登录');
        }
        $token = json_decode($token, true);
        if (!isset($token["token"]) || empty($token["token"])) {
            $this->respError('505', '请先登录');
        }
        return $token["token"];
    }

    /**
     * @desc 验签
     * @param $uid
     * @return bool
     */
    final public function checkSign($uid)
    {
        if (ENVIRONMENT === "localhost_banma") {
            return true;
        }
        $sign = $this->getParam2('sign');
        if (empty($sign)) {
            $this->respError('500', '缺少参数sign');
        }
        $token = $this->checkLogin($uid);
        $total_segments = $this->uri->total_segments();
        $segment_model = array();
        for ($i = 1; $i < $total_segments; $i++) {
            $segment_model[] = strtolower($this->uri->segment($i));
        }
        $url_model = strtolower(implode('/', $segment_model));
        $url_method = $this->uri->segment($total_segments);
        $validate_sign = md5("/index.php/{$url_model}/{$url_method}?token={$token}");
        if ($sign != $validate_sign) {
            $this->respError('505', '验签失败');
        }

    }

    /**
     * 加盐sign校验
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-04-18 15:02
     * @version
     * @param $uid
     * @return bool
     */
    final public function checkTicketSign($uid)
    {
        if (ENVIRONMENT === "localhost_banma" || ENVIRONMENT === "development_banma") {
            return true;
        }
        $sign = $this->getParam2('sign');
        if (empty($sign)) {
            $this->respError('500', '缺少参数sign');
        }
        $token = $this->checkLogin($uid);
//        $token = "DqfTphsfVApxzqOA";
        $total_segments = $this->uri->total_segments();
        $segment_model = array();
        for ($i = 1; $i < $total_segments; $i++) {
            $segment_model[] = strtolower($this->uri->segment($i));
        }
        $url_model = strtolower(implode('/', $segment_model));
        $url_method = $this->uri->segment($total_segments);
        unset($_REQUEST["sign"]);
        $_REQUEST["ticket"] = $this->sign_ticket;
        ksort($_REQUEST);
        $query = http_build_query($_REQUEST);
        $query = urldecode($query);
        $preSign = "/index.php/{$url_model}/{$url_method}?token={$token}&$query";
        $preSign = urlencode($preSign);
        $validate_sign = md5($preSign);
        if ($sign != $validate_sign) {
            $this->respError('505', '验签失败');
        }
        return false;
    }

    /**
     * @desc  解密用户的加密信息
     * @param $appid
     * @param $session_key
     * @param $iv
     * @param $encryptedData
     * @return mixed
     */
    final public function decryptWechatData($appid, $session_key, $iv, $encryptedData)
    {
        if (empty($appid) || empty($session_key) || empty($iv) || empty($encryptedData)) {
            return array();
        }
        $pc = new \WXBizDataCrypt($appid, $session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        $userInfo = json_decode($data, true);
        if ($errCode != 0) {
            return array();
        }
        return $userInfo;
    }

    /**
     * @desc 更新头像
     * @param $user_id
     * @param $avatarUrl
     */
    final public function uploadAvatar($user_id, $avatarUrl)
    {
        if (empty($user_id) || empty($avatarUrl)) {
            return false;
        }
        $ossCfg = Consts::OSS_CFG;
        $ossClient = new \OSS\OssClient($ossCfg['id'], $ossCfg['key'], $ossCfg['host']);
        $circleAvatarKey = "answer_king/avatar/avatar_36_circle_{$user_id}.jpg";
        $key1 = "answer_king/avatar/{$user_id}.jpg";
        $this->load->helper('curl_get');
        $avatar = curl_get($avatarUrl);
        if ($avatar) {
            try {
                $ossClient->putObject($ossCfg['bucket'], $key1, $avatar);
                // 获得oss圆形头像图片，并重新存储到oss:
                $options = array(
                    \OSS\OssClient::OSS_PROCESS => "style/avatar_36_circle"
                );
                $circleAvatar = $ossClient->getObject($ossCfg['bucket'], $key1, $options);
                // 裁剪后的路径
                $ossClient->putObject($ossCfg['bucket'], $circleAvatarKey, $circleAvatar);
            } catch (\OSS\Core\OssException $e) {
                $this->respError('500', '文件上传出错__005 ' . $e->getMessage());
            }
        }
        return true;
    }

    protected function getUserInfoById($user_id)
    {
        $sql = "select * from dgmn_user where id = ?";
        $result = $this->idiom_db->query($sql, $user_id)->row_array();
        return $result;
    }

    protected function updateScore($user_id, $score)
    {
        $cache = $this->redis_prefix . "world_ranking";
        if ($this->redis->exists($cache)) {
            $rank_score = $this->redis->ZSCORE($cache, $user_id);
            if ($rank_score === false || $rank_score < $score) {//只有排行榜里的值小于$score才更新
                $this->redis->ZADD($cache, $score, $user_id);
            }
        } else {
            $this->redis->ZADD($cache, $score, $user_id);
//            $this->redis->expire($cache, 7*24*60*60);
        }
    }


    protected function isCheckToken()
    {
        return false;
    }

    /**
     * 获得用户微信表单id  formid
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-05-24 14:27
     * @version
     * @param $openid
     * @return int
     */
    public function getFormIdTotal($openid)
    {
        $sql = "select count(*) as count  from zjdn_user_form_id where openid =? ";
        return (int)$this->idiom_db->query($sql, $openid)->row_array()['count'];
    }

    /**
     * 流量全网通  config
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-07-09 10:04
     * @version
     * @return array
     */
    protected function getLiuLiangQuanWangTongCfg()
    {
        return [
            "username" => "yongwang",
            "submit_url" => "http://liuliang.llqwt.com/api/submit",//提交流量包
            "get_meal_tag_url" => "http://liuliang.llqwt.com/api/getmealtag",//获取流量包编码
            "appkey" => "a0e1eab0a2fd44abafbcdf1d36f8b710"
        ];
    }

    protected function getMealOrderByOpenid($openid)
    {
        $sql = "select * from zjdn_meal_order where openid = ?";
        return $this->idiom_db->query($sql, $openid)->row_array();
    }

    /**
     * 获得流量包
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-07-10 09:27
     * @version
     * @param $phone
     * @return mixed|string
     */
    protected function getMealTag($phone)
    {
        $this->load->helper('curl_post');
        $cfg = $this->getLiuLiangQuanWangTongCfg();
        $time = time();
        $pre_md5 = "{$cfg['username']}$phone{$cfg['appkey']}$time";
        $digest = strtolower(md5($pre_md5));
        $res = curl_post($cfg['get_meal_tag_url'], [
            "username" => $cfg['username'],
            "mobile" => $phone,
            "timestamp" => $time,
            "digest" => $digest,
        ]);
        $res = json_decode($res, true);
        return $res;
    }

    protected function createOrderNo($n = 16)
    {
        $this->load->helper('api_helper');
        return create_token($n);
    }


    protected function getRedPacketTransactionByOpenid($openid)
    {
        $sql = "select * from zjdn_red_packet_transaction where openid = ?";
        return $this->idiom_db->query($sql, $openid)->row_array();
    }

    /**
     * 更新金币
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-08-02 17:04
     * @version
     * @param $user_id
     * @param $value
     * @param $desc
     * @param $seq_str
     * @param $action_type
     * @param int $item_type
     * @return string
     */
    protected function updateGoldCoin($user_id, $value, $desc, $seq_str, $action_type, $item_type = 1)
    {
        $sql = "update  dgmn_user set gold_coin = ? where id=?";
        $res = $this->idiom_db->query($sql, [$value, $user_id]);
        if ($res) {
            $data = [
                'user_id' => $user_id,
                'value' => $value,
                'item_type' => $item_type,
                'action_type' => empty($action_type) ? "" : $action_type,
                'desc' => empty($desc) ? "" : $desc,
                'create_time' => time(),
                "seq" => $seq_str
            ];
            $this->idiom_db->insert('dgmn_item_transaction', $data);
            $id = $this->idiom_db->insert_id();
        } else {
            return "更新金币数量失败";
        }
        return "success";
    }

    protected function jscode2session($code)
    {
        $weCfg = $this->getWxCfg();
        //使用code去换取微信的session_key，并获得openid
        $this->load->helper('curl_get');
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $weCfg['appid'] . "&secret=" . $weCfg['secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
        $apiRet = json_decode(curl_get($url), true);
        return $apiRet;
    }

    /**
     * 取消该笔扣除游戏币的订单
     * 文档 https://developers.weixin.qq.com/minigame/dev/api/midas-payment/midasCancelPay.html
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-10-08 17:54
     * @version
     * @param $user_id
     * @param $session_key
     * @param $openid
     * @param $env
     * @param $zone_id
     * @param $pf
     * @param $pay_item
     * @param $bill_no
     * @return array
     */
    protected function midasCancelPay($user_id, $session_key, $openid, $env, $zone_id, $pf, $pay_item, $bill_no, $try_number = 0)
    {

        $this->load->helper('curl_post');
        $access_token = $this->getDefaultWeiXinAccessToken();
        if (empty($access_token)) {
            return ['4002', '获得access_token失败'];
        }
        $domain = "https://api.weixin.qq.com";
        $app_key = "";
        if ($env == 1) {// 沙箱AppKey
            $app_key = $this->midas_sandbox_app_key;
            $uri = "/cgi-bin/midas/sandbox/cancelpay";
        } else {// 现网AppKey
            $app_key = $this->midas_app_key;
            $uri = "/cgi-bin/midas/cancelpay";
        }
        $cfg = $this->getWxCfg();
        $appid = $cfg["appid"];
        $time = time();
        $this->load->helper('api_helper');
        $user_ip = get_client_ip();
        $data = [
            "openid" => $openid,
            "appid" => $appid,
            "offer_id" => $this->offer_id,
            "ts" => $time,
            "zone_id" => $zone_id,
            "pf" => $pf,
            "user_ip" => $user_ip,
            "bill_no" => $bill_no,
            "pay_item" => $pay_item,
        ];
        $this->load->helper('midas');
        $data["sig"] = get_midas_sig($uri, "POST", $app_key, $data);
        $data["mp_sig"] = get_midas_mp_sig($uri, "POST", $session_key, $access_token, $data);
        $url = "{$domain}{$uri}?access_token=$access_token";
        $res = curl_post($url, json_encode($data));
        $res = json_decode($res, true);
        $this->updateMidasPayOrderByUseridAndBillNo($user_id, $bill_no, [
            "wx_cancel_errcode" => empty($res['errcode']) ? "" : $res['errcode'],
            "wx_cancel_errmsg" => empty($res['errmsg']) ? "" : $res['errmsg'],
            "wx_cancel_bill_no" => empty($res['bill_no']) ? "" : $res['bill_no'],
            "cancel_status" => empty($res['errcode']) && $res['errcode'] == 0 ? 1 : 2
        ]);
        if ($res['errcode'] == 0) {
            return [0, ""];
        } else if (isset($res["errcode"]) && $res['errcode'] == 40001 && $try_number < 3) {
            $this->delWeiXinAccessToken($cfg["appid"]);
            return $this->midasCancelPay($user_id, $session_key, $openid, $env, $zone_id, $pf, $pay_item, $bill_no, ++$try_number);
        } else if ($res['errcode'] == -1) {
            //取消支付
            return ['4003', "{$res['errmsg']}"];
        } else {
            return ['4004', "取消失败，{$res['errmsg']}"];
        }
    }

    protected function addMidasPayOrder($data)
    {
        return $this->idiom_db->insert('dgmn_midas_order', $data);
    }

    protected function updateMidasPayOrderByUseridAndBillNo($user_id, $bill_no, $data)
    {
        $this->idiom_db->where('user_id', $user_id);
        $this->idiom_db->where('bill_no', $bill_no);
        $this->idiom_db->update('dgmn_midas_order', $data);
    }

    /**
     * 获得accesstoken
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-10-08 14:13
     * @version
     * @return string
     */
    protected function getDefaultWeiXinAccessToken()
    {
        $cfg = $this->getWxCfg();
        return $this->getWeiXinAccessToken($cfg["appid"], $cfg["secret"]);
    }

    /**
     * 获得accesstoken
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-08-30 15:01
     * @version
     * @param $appid
     * @param $secret
     * @return string
     */
    protected function getWeiXinAccessToken($appid, $secret)
    {
        $key = $this->redis_prefix . "wx_access_token_$appid";
        $access_token = $this->redis->get($key);

        if (empty($access_token)) {
            $this->load->helper('curl_get');
            $as_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
//            $tmp = system("curl '$as_url'");
            $tmp = curl_get($as_url);
            $tmp = json_decode($tmp, true);
            if (empty($tmp) || !isset($tmp["access_token"])) {
                return null;
            }
            $access_token = $tmp["access_token"];
            $this->redis->setex($key, 2 * 60 * 60 - 10 * 60, $access_token);
            return $access_token;
        } else {
            return $access_token;
        }
    }

    protected function delWeiXinAccessToken($appid)
    {
        $key = $this->redis_prefix . "wx_access_token_$appid";
        $this->redis->del($key);
    }

}
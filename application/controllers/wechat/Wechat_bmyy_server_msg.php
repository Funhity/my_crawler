<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @api {post} wechat/Wechat_bmyy_server_msg 00. 斑马英语-消息接口
 * @apiName wechat_msg_Wechat_bmyy_server_msg
 * @apiVersion 1.0.0
 * @apiGroup WechatMp
 *
 * @apiDescription 微信公从号(斑马英语)消息接口对接<br />
 * <br />
 * @apiParam (管理员指令) {String} order___qrnew___qr_live_[live_id]_join 创建场景二维码(永久)指令
 * @apiParam (管理员指令) {String} order___getui 获取当前用户信息
 * @apiParam (管理员指令) {String} order___menuupdate 更新公众号菜单
 * @apiParam (管理员指令) {String} order___subscribe___[1/0] 模拟用户关注/取消关注公众号事件
 * @apiParam (管理员指令) {String} order___liveend___[live_id] 给某个live发送结束消息
 * <br />
 *
 * @apiSuccessExample {text} 带场景二维码逻辑
 *  系统注册检查
 *  对于公众号用户，如果接收到带参数二维码扫描事件（包括关注时检查），会先检查当前微信用户是否
 *  在系统内注册为了一个用户，如果没有注册，会先进行注册。
 *
 *  场景二维码分为两类：永久二维码和临时二维码
 *  1. 临时二维码为纯数字，业务分类如下:
 *    如果传递进来的场景值是一个大于6位的数字，并且是以10开始的，那么这是一个live分享海报的扫描事件，交到海报处理程序去处理:
 *  2. 永久二维码:
 *    永久二维码也分数字类型(1-100000)和字符串类型，目前只创建字符串类型的永久二维码，
 *    创建的永久二维码以qr作为起始字符串，后面以下划线分割的每一部分为业务内容及业务参数数据，根据业务去定义
 *      A、qr_live_sign_[live_id]: Live直播报名: 用户报名后，把用户添加到报名列表，开播时通知用户参与活动;
 *      B、qr_live_card_[live_id]: 为用户生成live的分享海报, 达到5人后为用户生成live订单
 *      C、qr_wegroup_join_[act_key]: 返回要加入的微信群, 后面的活动名对应数据库中t_wechat_group的act_key字段;
 *
 * @apiErrorExample {text} 用户分享海报生成逻辑
 *  1. 首先通过一个公众二维码入口进来: qr_live_card_[live_id], 系统收到这个场景值后，开始给用户生成二维码海报;
 *        海报生成程序首先会马上给用户返回海报生成中消息，然后通过一个异步的调用执行海报生成操作，生成完成后通过客服消息发送回给用户;
 *  2. 用户请朋友扫描后，系统收到以10开头，位数大于6的场景值，系统根据场景值还原出live_id, 海报所有者给用户记录扫描信息;
 *      如果达到指定人数，给用户生成live订单，并通知扫描者及海报拥有者;
 *      如果检测到扫描者还没有生成海报，那么同时执行一个异步请求为扫描者用户生成自己的海报。
 *      系统流程结束。
*/


require_once APPPATH . 'libraries/WeixinResponse/Image_response.php';
require_once APPPATH . 'libraries/WeixinResponse/Music_response.php';
require_once APPPATH . 'libraries/WeixinResponse/News_response.php';
require_once APPPATH . 'libraries/WeixinResponse/News_response_item.php';
require_once APPPATH . 'libraries/WeixinResponse/Text_response.php';
require_once APPPATH . 'libraries/WeixinResponse/Video_response.php';
require_once APPPATH . 'libraries/WeixinResponse/Video_response_lite.php';
require_once APPPATH . 'libraries/WeixinResponse/Voice_response.php';

/**
 * 微信公众号消息对接接口
 */
class Wechat_bmyy_server_msg extends MY_Controller {

    private $request;
    private $wx_interface_debug = false;
    private $wx_interface_token = 'ljDsdfMQljsdldWfjJdDjiOwjGe2Q832';
    private $weCfg = null;                      // 公众号配置文件
    private $admins = array(
        'oCDZpwlSr00w_6084HtemvHvy-Vk',         // 官海升
        'oCDZpwqodDiPgIpXbPFwDlKxteu4',         // 李焱杨
        'oCDZpwtS06GyqD0KYrAD_MX_vM1c',         // 马平
        'oCDZpwn3gFkPux1fE3MEncweeaog'
    );

    private $iv_id = 2001;             // 公众号当前活动的id;


    // 1. 负责对消息的验证及把XML数据转化为数组的形式，
    //	 将数组键名转换为小写，提高健壮性，减少因大小写不同而出现的问题, 保存在类成员$request中；
    // 下面三个验证的过程，获取$_GET参数不能使用I('get.xxx')，不知道为什么
    protected function processForUser($isLogin, $me, $token_type) {

        pushlog('receive raw data: ', $GLOBALS['HTTP_RAW_POST_DATA']);

        if ($this->isValid() && $this->validateSignature($this->wx_interface_token)) {
            pushlog('msg is invalid, signature valid failed', '');
            exit($_GET['echostr']);
        }
        //set_error_handler(array(&$this, 'errorHandler'));		// 打开这行无法同时发送客服消息与回复消息，待解决
        // 设置错误处理函数，将错误通过文本消息回复显示
        $xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->request = array_change_key_case($xml, CASE_LOWER);

        $this->weCfg = Consts::WECHAT_BMYY;
        $this->load->library('Weixin2/WeixinKefu', array('weCfg' => $this->weCfg));
        $this->load->library('Weixin2/WeixinQrscene', array('weCfg' => $this->weCfg));
        $this->load->library('Weixin2/WeixinMenu', array('weCfg' => $this->weCfg));
        $this->load->library('Weixin2/WeixinUser', array('weCfg' => $this->weCfg));

        $msgType = $this->getRequest('msgtype');
        $event = $this->getRequest('event');

        // 如果收到可以激活用户的信息，更新用户的激活时间: 激活的用户可以主动给其发送客服消息
        if(in_array($msgType, ['text', 'voice', 'video', 'shortvideo', 'image', 'link']) || ($msgType == 'event' && in_array($event, ['subscribe', 'CLICK', 'VIEW', 'SCAN'])) ) {
            $activeKey = ENVIRONMENT.':WECHAT:'.$this->weCfg['name'].':ACTIVE_OPENIDS:'.$this->getRequest('fromusername');
            $redis = RedisInstance::getInstance();
            $redis->setex($activeKey, 172800-180, $this->getRequest('fromusername'));      // 两天内为激活用户, 2天减3分钟
        }

        pushlog('recive data array: ', $this->getRequest());


        // 获得这个openid对应的用户信息:
        $sql = "select * from t_wechat_user where appid = ? and openid = ? limit 1;";
        $query = $this->db->query($sql, [$this->weCfg['appid'], $this->getRequest('fromusername')]);
        $weUser = $query->row_array();
        $this->weUser = $weUser;


        // ############################## 客服消息处理 #########################################
        // 如果是文字或图片消息，把记录添加到数据库，并通知客服人员:
        if($msgType == 'text' || $msgType == 'image' || $msgType == 'voice') {
            $msgContent = '';
            if($msgType == 'text') {
                $msgContent = $this->getRequest('content');
            } else if($msgType == 'image') {
                $this->load->helper('curl_get');
                $this->load->helper('ossupload');
                $imageContent = curl_get($this->getRequest('picurl'));
                $cdnUrl = ossupload($imageContent, 'wechat_kefu_msg', 'jpg', 'put');
                $msgContent = $cdnUrl;
            } else {
                $msgContent = '收到一条语音消息，这里暂不支持播放，请到微信后台查看';
            }
            // 把消息保存到数据库：
            $newData = array(
                'appid'         =>  $this->weCfg['appid'],
                'app_name_cn'   =>  $this->weCfg['name_cn'],
                'msgid'         =>  $this->getRequest('msgid'),
                'fromtype'      =>  1,                                  // 消息的来源类型: 1=客户发送消息; 2=客服回复消息
                'msgtype'       =>  $this->getRequest('msgtype'),       // 消息的媒体类型: 文字/图片
                'content'       =>  $msgContent,
                'openid'        =>  $this->getRequest('fromusername'),
                'session_openid'=>  $this->getRequest('fromusername'),
                'nickname'      =>  empty($weUser) ? '未注册用户' : $weUser['nickname'],
                'avatarurl'     =>  empty($weUser) ? '' : $weUser['avatar'],
                'datetime'      =>  time(),
                'datetime_str'  =>  date('Y-m-d H:i:s'),
            );
            $this->db->insert('t_kefu_msg', $newData);
            if($this->db->affected_rows() != 1) {
                pushlog('发送客服消息失败: ', $newData, 3);
            }
            pushlog('保存客服消息成功', $this->db->insert_id());

            // 获得公众号的token: 所有公众号的日志消息都统一转到斑马扫生办公众号，这样就不用所有公众号都信赖这个模板;
            $this->load->helper('wechat_token');
            $accessToken = wechat_token('BMYY');

            //通过公众号给客服人员推送一条消息:
            $kefuUrl = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$accessToken;
            $tempUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;

            $kf_token = Consts::KF_TOKEN;
            $kefuRespUrl = "https://api.zuiqiangyingyu.cn/kefu/index.html?kf_token=".$kf_token."&app=wechat_".$this->weCfg['name']."&session_openid=".$newData['session_openid'];          // 客服消息回复地址:
            $respOrd = "客服消息通知
应用来源:【".$this->weCfg['name_cn']."】
用户: ".$newData['nickname']." [".$newData['openid']."]
消息: ".$msgContent."\n".date('Y-m-d H:i:s')."
<a href=\"".$kefuRespUrl."\">点击回复</a>";
            foreach(Consts::BMYY_KF_LIST as $openid) {
                $json = json_encode(
                    array(
                        'touser' => $openid,
                        'msgtype' => 'text',
                        'text' => array('content' => $respOrd)
                    ), JSON_UNESCAPED_UNICODE);
                $textRet = curl_post($kefuUrl, $json);
                $textRetArr = json_decode($textRet, true);
                pushlog('text kefu send: ', $json);
                pushlog('send response', $textRet);
                if(is_array($textRetArr) && $textRetArr['errcode'] == 0) {
                    pushlog('text发送成功，不再推送模板消息, ', $textRet);
                    continue;
                }

                // Text客服消息没有发送成功，发送模板消息;
                $template = '{
				"touser":"'.$openid.'",
				"template_id":"wIsYINlGW-Ml6A6W-zTTbA206PevVSJn7Re1eAux6FA",
				"url": "'.$kefuRespUrl.'",
				"data":{
						"first": {
						  "value":"微信应用:【'.$this->weCfg['name_cn'].'】"
					    },
						"keyword1": {
						  "value":"'.$newData['nickname'].' ['.$newData['openid'].']"
					    },
						"keyword2": {
						  "value":"'.$msgContent.'"
					    },
						"remark":{
							"value":"'.date('Y-m-d H:i:s').'",
							"color":"#173177"
                   		}
				    }
			    }';
//              pushlog('模板消息内容: ', $template);
                $ret = curl_post($tempUrl, $template);
                pushlog('template kefu send ret: ', $ret);
            }
        }
        // ############################## 客服消息处理 #########################################



        switch ($msgType) {		// 消息类型
            case 'event':
                switch ($event) {
                    case 'subscribe':
                        $this->onSubscribe();
                        break;
                    case 'unsubscribe':
                        $this->onUnsubscribe();
                        break;
                    case 'CLICK':
                        $this->onClickMenuClick();
                        break;
                    case 'VIEW':		// 点击了view类型的菜单（买菜）
                        // Kefu::sendTextToAdmin('用户点击了买菜！');
                        $this->onViewMenuClick();
                        break;
                    case 'SCAN':
                        $this->onQrScan();
                        break;
                    case 'LOCATION':
                        $this->onLocation();
                        break;
                    default:
                        $this->onEventUnknown();
                        break;
                }
                break;

            case 'text':
                $this->onText($this->getRequest('content'));
                break;

            case 'voice':
                $this->onVoice();
                break;

            case 'video':
                $this->onVideo();
                break;

            case 'shortvideo':
                $this->onVideo();
                break;

            case 'image':
                $this->onImage();
                break;

            case 'location':
                $this->onLocation();
                break;

            case 'link':
                $this->onLink();
                break;
            default:
                $this->onUnknown();
                break;
        }
    }

    protected function isCheckToken() {
        return false;
    }



    // 2. 判断此次请求是否为验证请求
    private function isValid() {
        return isset($_GET['echostr']);
    }


    // 3. 判断验证请求的签名信息是否正确
    private function validateSignature($token) {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray);
        return sha1(implode($signatureArray)) == $signature;
    }


    // 5. 事件类型->关注事件 处理函数；主要完成三件事，关注场景记录，用户表更新及回复关注消息
    private function onSubscribe() {
        $eventkey = $this->getRequest('eventkey');
        $openid = $this->getRequest('fromusername');
        if(empty($eventkey)) $eventkey = '';
        // 添加用户信息到微信用户表:
        $this->loadModel('user/wechat_user');
        $this->wechat_user->mpSubscribeCheck($openid, 1, $eventkey, $this->weCfg);

        $eventkey = substr($eventkey,8);        // 关注时的事件扫描需要从第8位开始算。

        // 检查用户是否进行系统注册
        $this->wechat_user->userSystemRegisterCheck($openid, $this->weCfg, $eventkey);

        // 用户扫描的是带有场景值的二维码,则扫完二维码扫描事件处理
//        if(!empty($eventkey)) {   // 1706game走正常关注流程;
//            $this->onQrScan($eventkey);
//            exit('');
//        }

        $weUser = json_decode($this->weixinuser->getUserInfoById($openid), true);
        pushlog('weUser: ', $weUser);

        $this->weixinkefu->sendText($this->getRequest('fromusername'), 'Hi '.$weUser['nickname'].'，欢迎来到斑马英语^_^');
        $this->responseText('斑马英语联合美国外教推出了「180天口语提升计划」，每天跟读地道美语，轻松学会美语发音。

回复「报名」获得入学资格');
    }


    // 6. 事件类型->取消关注事件 处理函数；这里主要做一件事，更新用户表的level=4, 更新最后访问时间：
    private function onUnsubscribe() {
        $fromuser = $this->getRequest('fromusername');
        pushlog('user unsubscribe, openid: ', $fromuser);
        $this->loadModel('user/wechat_user');
        $this->wechat_user->mpSubscribeCheck($fromuser, 0, $this->weCfg);     // 取消关注，更新用户表;
    }


    // 7. 事件类型->用户点击（Click类型）菜单事件 处理函数；根据用户点击的菜单(Click类型)返回相应的数据
    private function onClickMenuClick() {
        $eventKey = strtolower($this->getRequest("eventkey"));
        pushlog('收到Click菜单点击事件：' . $eventKey, 3);
        switch ($eventKey) {
            case 'menu_click_21speak':
                $openid = $this->getRequest('fromusername');
                $this->loadModel('banma/bm_course');
                $this->bm_course->respUser21SpeakTodayCourse($openid, 'speak_english', $this->weCfg);
                exit('');
            default:
                $content = '未知菜单:'. $eventKey;
                break;
        }
        $this->responseText('收到Click菜单点击事件：' . $eventKey);
    }


    // 8. 事件类型->用户点击View类型）菜单事件 处理函数；
    private function onViewMenuClick() {
        $eventKey = strtolower($this->getRequest("eventkey"));
        pushlog('收到View菜单点击事件：' . $eventKey, 4);
        $this->responseText('收到View菜单点击事件：' . $eventKey);

    }


    /**
     * 事件类型->收到二维码扫描事件时触发
     * 这里包含已关注用户扫描与未关注用户扫描;
     * 已关注用户扫描没有场景值的二维码不会触发该函数执行，因此这里不用考虑无场景值的情况
     * @param null $eventKey 如果未传入eventKey，则从request中获取(已关注后的扫描), 如果有传入(未关注时的扫描)则从参数中带入
     */
    private function onQrScan($eventKey=null) {
//        if($eventKey == null) $eventKey = $this->getRequest('eventkey');	// 这里直接获取到的是eventkey，不用截取
        pushlog('receive qr scan, key is: ', $eventKey, 2);
        $openid = $this->getRequest('fromusername');

        // 检查用户是否进行系统注册
        $this->loadModel('user/wechat_user');
        $this->wechat_user->userSystemRegisterCheck($openid, $this->weCfg, $eventKey);

        // 用户从「斑马招生办的入学海报进来」返回默认消息;
//        if($eventKey == 'qr_cet46_inschool' || $eventKey == 'qr_1706game') {
            $weUser = json_decode($this->weixinuser->getUserInfoById($openid), true);
            pushlog('weUser: ', $weUser);
            $this->weixinkefu->sendText($this->getRequest('fromusername'), 'Hi '.$weUser['nickname'].'，欢迎来到斑马英语^_^');
            $this->responseText('斑马英语联合美国外教推出了「180天口语提升计划」，每天跟读地道美语，轻松学会美语发音。

回复「报名」获得入学资格');
//        }
        exit('');
    }


    // 10. 事件类型->收到未知类型的事件
    private function onEventUnknown(){
        $text = '收到未知类型事件：' . print_r($this->getRequest(), true);
         pushlog($text, 3);
        $this->defaultRespMsg();
    }


    // 11. 文本消息类型：收到文本消息时触发：
    //		为了让语音识别的信息能转发到这里处理，这个方法添加了一个$content参数作为传入
    private function onText($content) {
        $openid = $this->getRequest('fromusername');
        pushlog('receive text: ', $content);


        // 口语报名:
        if($content == '报名' || $content == '口语' || $content == '1' || $content == '報名' || $content == '进群') {
            // 检查用户是否进行系统注册
            $this->loadModel('user/wechat_user');
            $this->wechat_user->userSystemRegisterCheck($openid, $this->weCfg, '');

            // 获得用户的系统注册信息:
            $this->loadModel('user/wechat_user');
            $sysUser = $this->wechat_user->getSystemUserByWechatOpenId($openid, $this->weCfg['appid']);
            if(empty($sysUser)) {
                pushlog('未找到用户系统注册信息', $openid);
                exit($this->responseText('网络异常'));
            }
            $user_id = $sysUser['id'];
            pushlog('找到用户注册信息', $user_id);

            // 首先把用户加入到一个组中:
            $this->loadModel('user/wechat_user');
            $this->loadModel('user/user_group');
            $group_type = 'speak_english';
            $we_unionid = $sysUser['openid_wechat'];
            $result = $this->user_group->addUserToLimitedUserGroup($group_type, $user_id, $we_unionid);

            $this->weixinkefu->sendText($openid, '恭喜你获得入学资格，并获得一次邀请小伙伴的特权(分享入学通知书给好友)，一起提升口语，请点击下方链接开始学习↓↓↓');
            $this->weixinkefu->sendImage($openid, "3ftPSAkhaNx7mLm75c4Kr-Zz7NFgCg388p7cAVRUIJY");

            $this->loadModel('banma/bm_course');
            $this->bm_course->respUser21SpeakTodayCourse($openid, 'speak_english', $this->weCfg);
            exit('');
        }

        // 口语报名:
        if($content == '报名测试1234123412341235324534563474567465') {

            // 首先把用户加入到一个组中:
            $this->loadModel('user/wechat_user');
            $this->loadModel('user/user_group');
            $group_type = 'speak_english';
            $we_unionid = 'oGbfh0sVvL4cgUyya2reQiY3Sjfo';
            $user_id = 'U2017052100222737810';
            $openidt = 'oC15VwAYJXtpSGP0X28ulRStB6Ps';
            $result = $this->user_group->addUserToLimitedUserGroup($group_type, $user_id, $we_unionid);

            $this->weixinkefu->sendText($openid, '恭喜你获得入学资格，并获得一次邀请小伙伴的特权(分享入学通知书给好友)，一起提升口语，请点击下方链接开始学习↓↓↓');
            $this->weixinkefu->sendImage($openid, "3ftPSAkhaNx7mLm75c4Kr1w3ETumLe8TscvUS83rtkM");

            $this->loadModel('banma/bm_course');
            $this->bm_course->respUser21SpeakTodayCourse($openidt, 'speak_english', $this->weCfg);
            exit('');
        }



        $contentArray = explode('___', $content);
        if($contentArray[0] == 'order' && in_array($this->getRequest('fromusername'), $this->admins)) {
            switch($contentArray[1]) {
                case 'qrnew':
                    $qr_url = $this->weixinqrscene->getQrcodeUrl($contentArray[2]);
                    $data = array(
                        'scene'		=>	$contentArray[2],
                        'qr_url'	=>	$qr_url,
                        'datetime'	=>	time(),
                    );
                    $this->db->insert('t_wechat_qrcode', $data);
                    $affectedRows = $this->db->affected_rows();
                    if($affectedRows < 1) {
                        pushlog('创建二维码成功，但插入数据库失败', $qr_url);
                    }
                    exit($this->responseText($qr_url));
                case 'renewmenu':
                    $upRet = $this->weixinmenu->renewMenu();
                    exit($this->responseText($upRet));
                    break;
                case 'getmenu':
                    $upRet = $this->weixinmenu->getMenu();
                    exit($this->responseText($upRet));
                    break;
                case 'kefu':
                    pushlog('send kefu msg', time());
                    $this->weixinkefu->sendText($this->getRequest('fromusername'), time());
                    break;
                case 'temp':
                    $this->load->library('Weixin2/WeixinTemplate', array('weCfg' => $this->weCfg));
                    $tmpRet = $this->weixintemplate->liveBookOk($this->getRequest('fromusername'), 'test live', 'tonight', 'http://web.dolphinlive.net/h5/wd_live/api_index');
                    $tmpRet2 = $this->weixintemplate->liveBookedComming($this->getRequest('fromusername'), 'test live', 'tonight', 'http://web.dolphinlive.net/h5/wd_live/api_index');
                    $tmpRet3 = $this->weixintemplate->liveCommonComming($this->getRequest('fromusername'), 'test live', 'tonight', 'http://web.dolphinlive.net/h5/wd_live/api_index');
                    exit($this->responseText($tmpRet . "\n\n" . $tmpRet2 . "\n\n" . $tmpRet3));
                    break;
                case 'material':
                    $this->load->library('Weixin2/WeixinMaterial', array('weCfg' => $this->weCfg));
                    $ret = $this->weixinmaterial->getMaterialList($contentArray[2]);     // type=image/video/voice/news
                    exit($this->responseText($ret));
                    break;
                case 'subscribe':
                    $this->loadModel('user/wechat_user');
                    $this->wechat_user->mpSubscribeCheck($this->getRequest('fromusername'), $contentArray[2], 'test_event', $this->weCfg);
                    exit($this->responseText('Subscribe test, action: ' . $contentArray[2]));
                    break;
                case 'liveend':
                    $live_id = $contentArray[2];
                    $sql = "select l.id, l.roomid, l.live_title, l.start_time, l.end_time, u.nickname from t_live_act l, t_user u where l.user_id = u.id and l.id = ?;";
                    $query = $this->db->query($sql, [$live_id]);
                    $live = $query->row_array();
                    if(empty($live)) {
                        exit($this->responseText('找不到live: ' . $live_id));
                    }

                    // 检查是否已经发送过了:
                    $redis = RedisInstance::getInstance();
                    $redKey = ENVIRONMENT.':LIVE:WECHAT_CROND:'.$live_id.':live_end_msg_sended';
                    if($redis->exists($redKey)) {
                        exit($this->responseText("Live已发送过结束消息，请勿重复发送，\nlive_id: " . $live_id . "\nlive_title: " . $live['live_title'] . "\n开始时间: " . date('Y-m-d H:i:s', $live['start_time']+28800)));
                    }

                    // 设置发送记录，防止重复发送:
                    $redis->set($redKey, 'start_send');
                    $redis->expire($redKey, 3600*30);

                    $timeNow = time();
                    if($live['start_time'] > $timeNow) {
                        exit($this->responseText("Live尚未开始，不能发送结束消息\nlive_id: " . $live_id . "\nlive_title: " . $live['live_title'] . "\n开始时间: " . date('Y-m-d H:i:s', $live['start_time']+28800)));
                    }
                    if($live['end_time'] > $timeNow) {
                        exit($this->responseText("Live尚未结束，不能发送结束消息\nlive_id: " . $live_id . "\nlive_title: " . $live['live_title'] . "\n结束时间: " . date('Y-m-d H:i:s', $live['end_time']+28800)));
                    }
                    if($live['end_time']+10800 < $timeNow) {
                        exit($this->responseText("Live距离结束已超过3小时，不能发送结束消息, \nlive_id: " . $live_id . "\nlive_title: " . $live['live_title'] . "\n结束时间: " . date('Y-m-d H:i:s', $live['end_time']+28800)));
                    }
                    $this->loadModel('tools/netease_im_helper');
                    // 结束语
                    $msg = 'Thanks ' . $live['nickname'] . ' for the great share.
The live has ended, and new comers can replay the Live at any time. Looking forward to meeting you guys in next Live.';
                    $ext = array(
                        'type'      =>  '1',
                        'avatar'    =>  'http://static.dolphinlive.net/avatar/default/site_logo_50.png'
                    );
                    $result = $this->netease_im_helper->sendRoomMsg($live['roomid'], 'U2016062211225235012', $msg, $ext);
                    if($result) {
                        $redis->set($redKey, 'send_success');
                    } else {
                        $redis->set($redKey, 'send_failed');
                    }
                    exit($this->responseText($result));
                    break;
                case 'getui':
                    $this->load->library('Weixin2/WeixinUser', array('weCfg' => $this->weCfg));
                    $content = $this->weixinuser->getUserInfoById($this->getRequest('fromusername'));
                    exit($this->responseText($content));
                    break;
            }
        }

        $lcontent = strtolower($content);
        exit('');
    }


    // 12. 语音消息类型：收到语音消息时触发：
    //	   获取语音识别字段，然后交到文字回复方法去处理
    private function onVoice() {
        //$this->responseVoice($this->getRequest('mediaid'));
        $this->onText(substr($this->getRequest('Recognition'), 0, -3));
    }


    // 13. 视频消息类型：收到视频消息时触发：
    private function onVideo() {
        $this->responseVideo($this->getRequest('mediaid'));
        //$this->responseText('视频消息：MediaId: ' . $this->getRequest('mediaid') . ', ThumbId: ' . $this->getRequest('thumbmediaid'));
    }


    // 14. 图片消息类型：收到图片消息时触发：回复欢迎图文消息:
    private function onImage() {
//		$this->responseNews(WeixinTuwen::getDefaultTuWen());
//		$this->responseText('您已参加“门票抽奖”活动。请留下您的联系方式，抽奖中奖后我们将会有客服联系您~');
    }


    // 15. 位置消息类型：收到地理位置消息时触发，回复收到的地理位置
    private function onLocation() {
        $msg = $this->getRequest('latitude') . ',' . $this->getRequest('longitude');
        pushlog('收到了位置消息：' . $this->getRequest('latitude') . ',' . $this->getRequest('longitude'), 2);
    }


    // 16. 链接消息类型：收到链接消息时触发，回复收到的链接地址
    private function onLink() {
        $this->responseText('收到了链接：' . $this->getRequest('url'));
    }


    // 17. 收到未知类型消息时触发，回复收到的消息类型
    private function onUnknown() {
        pushlog('收到了未知类型消息: ', $this->getRequest());
        $this->responseText('收到了未知类型消息：' . $this->getRequest('msgtype'));
    }


    // 18. 回复文本消息方法：
    private function responseText($content, $funcFlag = 0) {
        exit(new Text_response($this->getRequest('fromusername'), $this->getRequest('tousername'), $content, $funcFlag));
    }


    // 19. 回复语音消息方法
    private function responseVoice($mediaId, $funcFlag = 0) {
        exit(new Voice_response($this->getRequest('fromusername'), $this->getRequest('tousername'), $mediaId, $funcFlag));
    }


    // 20. 回复视频消息
    private function responseVideo($mediaId, $title=NULL, $description=NULL) {
        if(empty($title) || empty($description)){
            // 如果回复消息中没有指定这两个参数，那么将使用素材里的标题和描述；
            exit(new Video_response_lite($this->getRequest('fromusername'), $this->getRequest('tousername'), $mediaId));
        }else{
            exit(new Video_response($this->getRequest('fromusername'), $this->getRequest('tousername'), $mediaId, $title, $description));
        }
    }


    // 21. 回复音乐消息
    private function responseMusic($title, $description, $musicUrl, $hqMusicUrl, $funcFlag = 0) {
        exit(new Music_response($this->getRequest('fromusername'), $this->getRequest('tousername'), $title, $description, $musicUrl, $hqMusicUrl, $funcFlag));
    }

    // 22. 回复图文消息
    private function responseNews($items, $funcFlag = 0) {
        exit(new News_response($this->getRequest('fromusername'), $this->getRequest('tousername'), $items, $funcFlag));
    }

    // 22.1. 回复图片消息
    private function responseImage($mediaId, $funcFlag = 0) {
        exit(new Image_response($this->getRequest('fromusername'), $this->getRequest('tousername'), $mediaId, $funcFlag));
    }

    /**
     * 23. 自定义的错误处理函数，将 PHP 错误通过文本消息回复显示
     * @param  int $level   错误代码
     * @param  string $msg  错误内容
     * @param  string $file 产生错误的文件
     * @param  int $line    产生错误的行数
     * @return void
     */
    private function errorHandler($level, $msg, $file, $line) {
        if ( ! $this->debug) {
            return;
        }

        $error_type = array(
            // E_ERROR             => 'Error',
            E_WARNING           => 'Warning',
            // E_PARSE             => 'Parse Error',

            E_NOTICE            => 'Notice',
            // E_CORE_ERROR        => 'Core Error',
            // E_CORE_WARNING      => 'Core Warning',
            // E_COMPILE_ERROR     => 'Compile Error',
            // E_COMPILE_WARNING   => 'Compile Warning',
            E_USER_ERROR        => 'User Error',
            E_USER_WARNING      => 'User Warning',
            E_USER_NOTICE       => 'User Notice',
            E_STRICT            => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User Deprecated',
        );

        $template = <<<ERR
PHP 报错啦！

%s: %s
File: %s
Line: %s
ERR;
        $this->responseText(sprintf($template,
            $error_type[$level],
            $msg,
            $file,
            $line
        ));
    }


    // 24. 获取接收的XML数据中的的某个参数值，不区分大小写
    private function getRequest($param = FALSE) {
        if ($param === FALSE) {
            return $this->request;
        }
        $param = strtolower($param);
        if (isset($this->request[$param])) {
            return $this->request[$param];
        }
        return NULL;
    }



    // #####################################################################################
    // 业务逻辑想着的事件处理:
    // #####################################################################################

    // 默认返回消息:
    // 根据用户的报名情况返回用户的报名状态:
    private function defaultRespMsg($iv_id=null) {

        if($iv_id == null) {
            exit($this->responseText('欢迎入学斑马英语，点击下方菜单进入课程。如有疑问，欢迎留言咨询。'));
        }

        $this->loadModel('banma/bm_invite');
        $actInfo = $this->bm_invite->getInviteInfoByWechat($this->getRequest('fromusername'), $this->weCfg['appid'], $iv_id);
        if($actInfo != null) {
            // 如果已存在课程订单，那么直接返回进入课程
            if($actInfo['attend_info']['is_order'] == 1) {
                if($iv_id == 2001) {
                    exit($this->responseText("欢迎入学斑马英语，点击下方「进入学院学习课程」。如有疑问，欢迎留言咨询。"));
                } else if($iv_id == 2002) {
                    exit($this->responseText("欢迎入学斑马英语「21天口语计划」，点击下方「21天口语计划」获取当天课程。如有疑问，欢迎留言咨询。"));
                }
            }
        }
        switch($iv_id) {
            case 2001:
                $content = '您暂时未获得入学资格， <a href="http://web.dolphinlive.mobi/zebra_app/activity.html">点击获取入学资格</a>';
                break;
            case 2002:
                $content = '您暂时未获得入学资格， <a href="http://web.dolphinlive.mobi/zebra_app/activity_2002.html?activity_id=2002">点击获取入学资格</a>';
                break;
            default:
                $content = "欢迎入学斑马英语，点击下方菜单进入课程。如有疑问，欢迎留言咨询。";
        }
        exit($this->responseText($content));
    }


    /**
     * 处理Live预订二维码扫描事件:
     */
    private function liveBookQrscanProcess($eventKey)
    {
        $fromuser = $this->getRequest('fromusername');
        $keyArr = explode('_', $eventKey);
        $this->loadModel('db/live_book');
        $this->load->library('Weixin2/WeixinTemplate', array('weCfg' => $this->weCfg));
        $liveData = $this->live_book->liveBookFromWechatMp($fromuser, $keyArr[3], $eventKey);
        if ($liveData == null) {
            exit($this->responseText('欢迎关注Wonder Live，点击下方菜单查看最新Live分享。'));
        }
        // 报名成功，根据liveData给用户反馈:
        $live_date = date('m-d H:i', $liveData['start_time'] + 28800) . ' (GMT-8)';        // +8小时转换为东8区
        $live_url = "http://web.dolphinlive.net/h5/wd_live/api_detail?live_id=" . $liveData['id'] . '&channel=booked_ok_template_push';
        $tmpRetJson = $this->weixintemplate->liveBookOk($liveData, $liveData['live_title'], $live_date, $live_url);
        $tmpRet = json_decode($tmpRetJson, true);
        if ($tmpRet['errcode'] != 0) {
            // 推送失败, 回复一条文本消息:
            $respText = '您已成功报名' . $liveData['nickname'] . '的直播, <a href="' . $live_url . '">点击进入直播间</a>。';
            exit($this->responseText($respText));
        }
        // 发送成功, 回复一个空
        exit('');
    }
}

?>

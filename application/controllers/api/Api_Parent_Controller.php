<?php
/**
 * api 父类
 *
 * @since 20170926
 * @author pinheng
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Api_Parent_Controller extends MY_Controller
{
    public $redis;

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
        // 关闭redis连接
        ! empty($this->redis) && $this->redis->close();
    }

    // 根据参数wechat_type返回不同信息
    function getInfoByParamType($type = '')
    {
        $outArr = array();
        empty($type) && $type = $this->getParam2('wechat_type');
        switch ($type) {
            case 'wechat_song':
                // 猜歌
                
                //$outArr['wxCfg'] = Consts::WECHAT_GUESS_SONG;
                
                // 表前缀
                $tablePrefix = 't_guess_song_';
                
                // token前缀
                $redisPrefix = "song_";
                
                $outArr['tableUser'] = $tablePrefix . 'users';
                $outArr['tableUserInfo'] = $tablePrefix . 'user_info';
                $outArr['tableUserAnswer'] = $tablePrefix . 'user_answer';
                $outArr['tableUserHelp'] = $tablePrefix . 'user_help';
                $outArr['tableUserPrompt'] = $tablePrefix . 'user_prompt';
                
                $outArr['redisUserKey'] = $this->getRedisKey($redisPrefix . 'user');
                $outArr['redisTokenKey'] = $this->getRedisKey($redisPrefix . 'token');
                $outArr['redisMainKey'] = $this->getRedisKey($redisPrefix . 'main');
                $outArr['redisHelpKey'] = $this->getRedisKey($redisPrefix . 'help');
                $outArr['redisShareKey'] = $this->getRedisKey($redisPrefix . 'share');

                $outArr['redisFormKey'] = $this->getRedisKey($redisPrefix . 'form');
                $outArr['redisFormUserKey'] = $this->getRedisKey($redisPrefix . 'formuser');
                break;
            
            case 'wechat_singsong':
                // 唱唱猜猜
                
                $outArr['wxCfg'] = Consts::WECHAT_SING_SONG;
                
                // 表前缀
                $tablePrefix = 't_sing_guess_';
                
                // token前缀
                $redisPrefix = "sing_guess_";
                
                $outArr['tableUser'] = $tablePrefix . 'users';
                $outArr['tableUserInfo'] = $tablePrefix . 'user_info';
                $outArr['tableUserFriend'] = $tablePrefix . 'user_friend';
                
                $outArr['redisUserKey'] = $this->getRedisKey($redisPrefix . 'user');
                $outArr['redisTokenKey'] = $this->getRedisKey($redisPrefix . 'token');
                break;
            
            case 'wechat_pintu':
                // 拼图pk
                $outArr['wxCfg'] = Consts::WECHAT_JIGSAW;
                
                // 表前缀
                $tablePrefix = 't_jigsaw_';
                
                // token前缀
                $redisPrefix = "jigsaw_";
                
                $outArr['tableUser'] = $tablePrefix . 'users';
                $outArr['tableUserInfo'] = $tablePrefix . 'user_info';
                
                $outArr['redisUserKey'] = $this->getRedisKey($redisPrefix . 'user');
                $outArr['redisTokenKey'] = $this->getRedisKey($redisPrefix . 'token');
                break;
        }
        empty($outArr) && $this->respError(500, '非法请求');
        empty($outArr['tableUser']) && $this->respError(500, '非法请求');
        empty($outArr['tableUserInfo']) && $this->respError(500, '非法请求');
        empty($outArr['redisUserKey']) && $this->respError(500, '非法请求');
        empty($outArr['redisTokenKey']) && $this->respError(500, '非法请求');
        
        return $outArr;
    }
    
    // 获取redis的key，方便统一管理。后台该方法跟接口保持一致
    function getRedisKey($type, $param = '')
    {
        $val = '';
        switch ($type) {
            
            case 'illegal_words':
                // 非法词
                $val = ':ILLEGAL_WORDS';
                break;
            
            /**
             * 1=猜歌 start
             */
            case 'song_user':
                // 用户信息 . $user_id
                $val = ':U1:';
                break;
            
            case 'song_token':
                // 根据token找到相应uid token->uid
                $val = ':T1:';
                break;
            
            case 'song_main':
                // 猜歌，主表内容
                $val = ':M1';
                break;
            
            case 'song_help':
                // 猜歌，帮助信息
                $val = ':H1:U:';
                break;
                
            case 'song_user_form':
                // 小程序用户的表单formid
                $val = ':WEAPP_GUESS_SONG:user_form:';
                break;
                
            case 'song_form_user':
                // 小程序有formid的用户id
                $val = ':WEAPP_GUESS_SONG:form_user';
                break;
            
            /**
             * 1=猜歌 end
             */
            
            /**
             * 2=唱唱猜猜 start
             */
            case 'sing_guess_user':
                // 用户信息 . $user_id
                $val = ':U2:';
                break;
            
            case 'sing_guess_token':
                // 根据token找到相应uid token->uid，后面需要加上token
                $val = ':T2:';
                break;
            
            case 'sing_song_file':
                // 根据id找到相应作品信息，后面需要加上作品id
                $val = ':M2:F:';
                break;
            
            case 'sing_song_praise':
                // 点赞记录保存，后面需要加上用户id
                $val = ':M2:P:';
                break;
            
            case 'sing_song_answer':
                // 回答，这里zset只存了uid，后面需要加上作品id
                $val = ':M2:A:';
                break;
            
            case 'sing_song_answer_detail':
                // 回答详情，跟sing_song_answer结合，后面需要加上 $id.'_'. $uid
                $val = ':M2:AD:';
                break;
            
            case 'sing_song_rec':
                // 推荐
                $val = ':R2';
                break;
                
           case 'sing_access_token':
                $val = ':WECHAT:SING_SONG:access_token';
                break;
                
           // 用户的好友列表
           case 'sing_user_friend':
                $val = ':WEAPP_SING_SONG:ufriend:';
                break;
				
			// 总排行缓存
			case 'sing_top_all' :
				$val = ':WEAPP_SING_SONG:top_all';
				break;
			
			// 好友排行缓存
			case 'sing_top_friend' :
				$val = ':WEAPP_SING_SONG:top_friend:';
				break;
			
			// 全球排行集合
			case 'sing_top_all_zset' :
				$val = ':WEAPP_SING_SONG:top_all_zset';
				break;
			
			// 用户的全球排行刷新标记
			case 'sing_top_all_flag' :
				$val = ':WEAPP_SING_SONG:top_all_flag';
				break;
			
			// 用户的好友排行刷新标记
			case 'sing_top_friend_flag' :
				$val = ':WEAPP_SING_SONG:top_friend_flag';
				break;
            
            /**
             * 2=唱唱猜猜 end
             */
            
            /**
             * 3=拼图pk start
             */
            case 'jigsaw_user':
                // 用户信息 . $user_id
                $val = ':U3:';
                break;
            
            case 'jigsaw_token':
                // 根据token找到相应uid token->uid，后面需要加上token
                $val = ':T3:';
                break;
        
        /**
         * 3=拼图pk end
         */
        }
        return $val == '' ? '' : ENVIRONMENT . $val . $param;
    }

    /**
     * 生产24位订单号
     *
     * @param $preKey 订单前缀，通过这个区分不同小程序            
     *
     * @return string
     */
    function createOrderNum($preKey = 'o')
    {
        try {
            // 订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
            $order_id_main = date('YmdHis', time() + 28800) . rand(10000000, 99999999);
            // 订单号码主体长度
            $order_id_len = strlen($order_id_main);
            $order_id_sum = 0;
            for ($i = 0; $i < $order_id_len; $i ++) {
                $order_id_sum += (int) (substr($order_id_main, $i, 1));
            }
            // 唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
            return $preKey . $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            $this->respError(6, '订单生成失败，请重试！');
        }
    }
    
    // 开启事务，主要是因为只有生产环境才有阿里云的那一句，所以这里要区分下
    function beginTrans()
    {
        // 分布式數據庫的全局事務
        // 關閉單機事務
        $this->db->query("set autocommit=false");
    
        if (! in_array(ENVIRONMENT, array(
            'development_banma',
            'localhost_banma'
        ))) {
            // 初始化txc事務
            $this->db->query("select last_txc_xid()");
        }
    
        // 开启事务
        $this->db->trans_begin();
    }
    
    // 根据token返回用户信息
    function getUidByParamToken($require=true)
    {
    	// 查出uid
    	$this->redis = RedisInstance::getInstance();
    	
        $token = $this->getParam2('token');
        if($require){
        	(empty($token) || strlen($token) != 32) && $this->respError(4, '登录信息已过期，请重新进入。');
        }elseif(empty($token)){
        	return 0;
        }
        
        $uid = $this->redis->get($this->info['redisTokenKey'] . $token);
        
        empty($uid) && $this->respError(4, '登录信息已过期，请重新进入。');
        
        return (int)$uid;
    }

    // 查出用户信息
    function getUserInfo($uid, $selectArr = array())
    {
        $redisKey = $this->info['redisUserKey'] . $uid;
        
        empty($this->redis) && $this->redis = RedisInstance::getInstance();
        
        if ($this->redis->exists($redisKey)) {
            if (empty($selectArr)) {
                return $this->redis->hgetall($redisKey);
            }else{
            	$detail = $this->redis->hmget($redisKey, $selectArr);
            	
            	// 兼容之前redis中没有缓存openid，则不用缓存，调到下面重新获取openid缓存
            	if(!(in_array('openid', $selectArr) && empty($detail['openid']))){
            		 return $detail;
            	}
            }  
        }
        
        // 从mysql中找，然后放入redis
        $detail = $this->db->query("SELECT id,avatar,nickname,openid from {$this->info['tableUser']} where id=? and status=1  ", array(
            $uid
        ))->row_array();
        if (empty($detail)) {
            return '';
        }
        
        // 存入redis
        $pipe = $this->redis->multi(Redis::PIPELINE);
        
        // 设置redis
        $pipe->hmset($redisKey, $detail);
        
        // 存30天
        $pipe->expire($redisKey, 2592000);
        $pipe->exec();
        return $detail;
    }

    /**
     * 批量查出用户头像和昵称
     * 这里如果没有在redis里面找到，会去找mysql
     *
     * @param $uidArr 用户uid数组            
     */
    function getBatchUserInfo($uidArr)
    {
        $pipe = $this->redis->multi(Redis::PIPELINE);
        
        foreach ($uidArr as $v) {
            $pipe->hgetall($this->info['redisUserKey'] . $v);
        }
        $list = $pipe->exec();
        
        $retArr = array();
        foreach ($list as $v) {
            if (empty($v)) {
                continue;
            }
            // 下面这些是在redis里面有值的
            $retArr[$v['id']] = array(
                'nickname' => $v['nickname'],
                'avatar' => $v['avatar']
            );
            
            // 排除已经查找出来的，剩下的就是redis没存的
            unset($uidArr[$v['id']]);
        }
        
        if (empty($uidArr)) {
            // 查的用户全部在redis里面
            return $retArr;
        }
        
        // 从mysql中查出相应信息
        $mysqlList = $this->db->query("SELECT id,avatar,nickname from {$this->info['tableUser']} where id in(" . implode(',', $uidArr) . ") and status=1  ")->result_array();
        
        if (empty($mysqlList)) {
            return $retArr;
        }
        
        // 存入redis
        $pipe = $this->redis->multi(Redis::PIPELINE);
        foreach ($mysqlList as $v) {
            $retArr[$v['id']] = array(
                'nickname' => $v['nickname'],
                'avatar' => $v['avatar']
            );
            
            // 设置redis
            $pipe->hmset($this->info['redisUserKey'] . $v['id'], $v);
            // 存30天
            $pipe->expire($this->info['redisUserKey'] . $v['id'], 2592000);
        }
        $pipe->exec();
        return $retArr;
    }
    
    // 取得字数，长度，中文、数字、英文都算一个长度
    public function calStrLen($str)
    {
        return mb_strlen($str, 'UTF-8');
    }

    /**
     * 过滤字符
     *
     * @param $str 串            
     * @param $notArr 需要排除的字符            
     * @param $addArr 需要增加的字符            
     */
    function filterChar($str, $notArr = array(), $addArr = array())
    {
        $arr = array(
            '"' => '',
            "'" => '',
            '%' => '',
            '?' => '',
            "\\" => '',
            '~' => '',
            '`' => '',
            '!' => '',
            '@' => '',
            '#' => '',
            '$' => '',
            '%' => '',
            '^' => '',
            '&' => '',
            '*' => '',
            '<' => '',
            '>' => '',
            ':' => '',
            ';' => '',
            '+' => '',
            '=' => '',
            '/' => ''
        );
        if (! empty($addArr) && is_array($addArr)) {
            foreach ($addArr as $v) {
                if (! empty($v)) {
                    $arr[$v] = '';
                }
            }
        }
        if (! empty($notArr) && is_array($notArr)) {
            foreach ($notArr as $v) {
                if (! empty($v) && isset($arr[$v])) {
                    unset($arr[$v]);
                }
            }
        }
        return trim(strtr($str, $arr));
    }

    /**
     * 根据url 的page +perpage 获取limit 语句 和 start end（redis用）
     */
    function getParamFY($perpage = 20, $page = 0)
    {
        // 页号，从1开始
        if (empty($page)) {
            $page = $this->getParam2('page');
            empty($page) && $page = 1;
        }
        ! $this->isInt($page) && $page = 1;
        $page > 1000 && $page = 1;
        
        // 每页显示数，默认20
        empty($perpage) && $perpage = 20;
        $perpage > 1000 && $perpage = 20;
        
        $start = (int) ($page - 1) * $perpage;
        return array(
            'page' => $page,
            'perpage' => $perpage,
            'limit' => " LIMIT {$start},{$perpage} ",
            'start' => $start,
            'end' => $start + $perpage - 1
        );
    }
    
    // 查出用户头像和昵称
    function getUserAvatarNickName($user_id, $redis = '')
    {
        $redisKey = $this->getRedisKey('user', $user_id);
        empty($redis) && $redis = RedisInstance::getInstance();
        
        if ($redis->exists($redisKey)) {
            // 直接从redis返回
            return $redis->hgetall($redisKey);
        }
        
        // 从mysql获取
        $userInfo = $this->db->query("select id,avatar,nickname from t_user where id='{$user_id}' and status=1 ")->row_array();
        if (empty($userInfo)) {
            return false;
        }
        
        // 设置redis
        $redis->hmset($redisKey, $userInfo);
        // 存30天
        $redis->expire($redisKey, 2592000);
        return $userInfo;
    }

    /**
     * 批量查出用户头像和昵称
     *
     * @param $uidArr 用户user_id数组            
     */
    function getBatchUserAvatarNickName($uidArr, $redis = '')
    {
        empty($redis) && $redis = RedisInstance::getInstance();
        $pipe = $redis->multi(Redis::PIPELINE);
        
        $redisKey = $this->getRedisKey('user');
        foreach ($uidArr as $v) {
            $redis->hgetall($redisKey . $v);
        }
        $list = $pipe->exec();
        
        $retArr = array();
        foreach ($list as $v) {
            if (empty($v)) {
                continue;
            }
            // 下面这些是在redis里面有值的
            $retArr[$v['id']] = array(
                'nickname' => $v['nickname'],
                'avatar' => $v['avatar']
            );
            
            // 排除已经查找出来的，剩下的就是redis没存的
            unset($uidArr[$v['id']]);
        }
        
        if (empty($uidArr)) {
            // 查的用户全部在redis里面
            return $retArr;
        }
        
        // 从mysql中查出相应信息
        $ids = $sp = '';
        foreach ($uidArr as $v) {
            $ids .= $sp . "'{$v}'";
            $sp = ',';
        }
        
        $mysqlList = $this->db->query("SELECT id,avatar,nickname from t_user where id in({$ids}) and status=1  ")->result_array();
        if (empty($mysqlList)) {
            return $retArr;
        }
        
        // 存入redis
        $pipe = $redis->multi(Redis::PIPELINE);
        foreach ($mysqlList as $v) {
            $retArr[$v['id']] = array(
                'nickname' => $v['nickname'],
                'avatar' => $v['avatar']
            );
            
            // 设置redis
            $pipe->hmset($redisKey . $v['id'], $v);
            // 存30天
            $pipe->expire($redisKey . $v['id'], 2592000);
        }
        $pipe->exec();
        return $retArr;
    }

    /**
     * 随机数
     *
     * @param $whArr 生成类型1=数字，2=字符串小写，3=字符串大写，4=特殊字符，默认1，2，3            
     */
    function randStr($len, $whArr = array())
    {
        if (empty($whArr)) {
            // 默认1，2，3
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        } else {
            $chars = '';
            in_array(1, $whArr) && $chars .= '0123456789';
            in_array(2, $whArr) && $chars .= 'abcdefghijklmnopqrstuvwxyz';
            in_array(3, $whArr) && $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            in_array(4, $whArr) && $chars .= '~!@#$%^&*(_-|,)';
        }
        $charslen = strlen($chars) - 1;
        $string = '';
        for (; $len >= 1; $len --) {
            $position = rand(0, $charslen);
            $string .= $chars{$position};
        }
        return $string;
    }

    /**
     * 截字
     *
     * @param $string 字符串            
     * @param $sublen 长度            
     * @param $suffix 前缀            
     * @param $start 开始            
     * @return void|unknown|string
     */
    public function cnSubStr($string, $sublen, $suffix = '', $start = 0)
    {
        if ($sublen >= strlen($string)) {
            return $string;
        }
        $i = 0;
        while ($i < $start) {
            if (ord($string[$i]) > 127) {
                $i = $i + 3; // gb2312 +2 ; utf-8 +3
            } else {
                $i ++;
            }
        }
        $start = $i;
        
        if ($sublen == '') {
            return substr($string, $start);
        } elseif ($sublen > 0) {
            $end = $start + $sublen;
            while ($i < $end) {
                if (ord($string[$i]) > 127) {
                    $i = $i + 3;
                } else {
                    $i ++;
                }
            }
            $end = $i;
            $length = $end - $start;
            return substr($string, $start, $length) . $suffix;
        } elseif ($sublen == 0) {
            return;
        }
    }

    /**
     * 判断是否有敏感词
     *
     * @param $str 要检查的串            
     * @param $returnDetail 是否返回检查到的敏感词，默认不返回            
     */
    public function isIllegalStr($str, $returnDetail = false)
    {
        // 读取敏感词库
        $redis = RedisInstance::getInstance();
        $illegalWords = $redis->smembers($this->getRedisKey('illegal_words'));
        empty($illegalWords) && $illegalWords = array();
        
        // 加上符号
        $illegalWords[] = '"';
        $illegalWords[] = "'";
        $illegalWords[] = "\\";
        $illegalWords[] = '`';
        $illegalWords[] = '<';
        $illegalWords[] = '>';
        $illegalWords[] = '/';
        $count = count($illegalWords);
        
        $flag = false;
        for ($i = 0; $i < $count; $i ++) {
            $content = substr_count($str, $illegalWords[$i]);
            if ($content > 0) {
                $flag = $returnDetail ? $illegalWords[$i] : true;
                break;
            }
        }
        return $flag;
    }

    /**
     * +----------------------------------------------------
     * 上传文件到阿里云
     * +----------------------------------------------------
     */
    function uploadFile($file, $ext, $ossKey)
    {
        $ossCfg = Consts::OSS_CFG;
        $ossClient = new \OSS\OssClient($ossCfg['id'], $ossCfg['key'], $ossCfg['host']);
        return $ossClient->uploadFile($ossCfg['bucket'], $ossKey . '.' . $ext, $file);
    }

    /**
     * +----------------------------------------------------
     * 将文件保存到本地
     * +----------------------------------------------------
     */
    function saveFile($file, $path)
    {
        $handle = fopen($file, "w");
        fwrite($handle, $path);
        fclose($handle);
    }

    /**
     * 小程序码（测试备用）
     *
     * @param [type] $path_url
     *            [description]
     * @return [type] [description]
     */
    function getWeappCode($param, $pathUrl, $width, $redis = '')
    {
        $this->load->helper('curl_post');
        empty($redis) && $redis = RedisInstance::getInstance();
        $token = $redis->get(ENVIRONMENT . ':WECHAT:CHENYUCAICAICAI:access_token2');
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
    function getSQCode($path_url)
    {
        $this->load->helper('curl_post');
        $redis = RedisInstance::getInstance();
        $token = $redis->get(ENVIRONMENT . ':WECHAT:CHENYUCAICAICAI:access_token2');
        // $token = 'PwA4-disUjUW-QIJ5J4PkclF8ey6p86Q2TqqFnKimS6c2bzSVvRAQ2HsrLBbkI_J66p06rQPNY_7B0KHGAdCwkMDDVIUnMZrKOXBccUtqOpltQSFA2x9YoZ0dgytPMk7XESaACAKRF';
        
        $url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=" . $token;
        
        $json = json_encode(array(
            'path' => $path_url, // 扫码
            'width' => 280
        ), JSON_UNESCAPED_UNICODE);
        $textRet = curl_post($url, $json);
        return $textRet;
    }

    protected function isCheckToken()
    {
        return false;
    }
    
    // 获取oss图片域名
    function getImgHost()
    {
        return "https://static.zuiqiangyingyu.cn/";
    }
    
    // 输出格式化时间
    function outTimeFmt($t)
    {
        $time = time();
        if (empty($t)) {
            // 时间丢失，这种情况少
            return "很久前";
        }
        
        $diff = $time - $t;
        $day = floor($diff / 86400);
        if ($day > 0) {
            return $day . "天前";
        }
        
        $free1 = $diff % 86400;
        if ($free1 < 1) {
            return "刚刚";
        }
        
        $hour = floor($free1 / 3600);
        if ($hour > 0) {
            return $hour . "小时前";
        }
        
        $free2 = $free1 % 3600;
        if ($free2 < 1) {
            return "刚刚";
        }
        
        $min = floor($free2 / 60);
        if ($min > 0) {
            return $min . "分钟前";
        }
        
        $free3 = $free2 % 60;
        if ($free3 > 0) {
            return $free3 . "秒前";
        }
        return "刚刚";
    }

    /**
     * 随机分红包
     *
     * @param $total 红包金额，单位分            
     * @param $num 数量            
     * @param $isRetString 返回金额串标识，默认返回数组            
     *
     * @return false 错误
     * @return array 分配的数组
     * @return string 分配的金额串，以","分隔
     */
    function genRandRedpack($total, $num, $isRetString = false)
    {
        // 限制最小1元
        if ($total < 100) {
            return false;
        }
        
        // 限制单个红包最小1分
        if ($total / $num < 1) {
            return false;
        }
        $numOld = $num;
        $outArr = array();
        
        // 这个记录>2的一个序号
        $money2Flag = 0;
        
        $xh = 1;
        while (true) {
            
            if ($total == 0) {
                $money = 0;
            } elseif ($num == 1) {
                $money = $total;
            } else {
                $money = mt_rand(1, $total / $num * 2);
            }
            
            if ($money > 1) {
                $money2Flag = $xh;
            }
            
            $num --;
            $total -= $money;
            $outArr[$xh] = $money;
            $xh ++;
            if (count($outArr) == $numOld) {
                break;
            }
        }
        
        // 处理分配里面最后一个为0的情况
        if ($outArr[$numOld] < 1) {
            $outArr[$money2Flag] = $outArr[$money2Flag] - 1;
            $outArr[$numOld] = 1;
        }
        return $isRetString ? implode(',', $outArr) : $outArr;
    }

    function getNetHost()
    {
        return "https://api.zuiqiangyingyu.net/";
    }

    protected function processForUser($isLogin, $me, $token_type)
    {}
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/RedisInstance.php';

/**
 * Class MY_Controller
 * 请求参数及应用入口相关
 * 用户身份授权相关
 * 资源/库 加载
 * 常用信息获取缓存
 * 数据返回封装
 * 媒体资源加载相关
 * 日志及其它相关
 * 测试相关
 */
abstract class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // 所有接口，统一使用utc时间
        date_default_timezone_set('UTC');
        // date_default_timezone_set('Australia/Currie');
        // date_default_timezone_set('Asia/Shanghai');
        // 字符串助手
        $this->load->helper('string');
        // 加载随机库
        $this->load->library('randomtool');
        // 加载返回码
        $this->load->library('respcode');
        
        // 加载网络库
        $this->load->library('httptool');
        
        // 加载常量类: 根据环境来加载不同的类库
        $this->load->library(ENVIRONMENT . '/consts');
        
        // 文件助手
        $this->load->helper('file');
        
        // 加载url工具
        $this->load->helper('url');
        
        // 加载多语言支持
        $this->loadLangs();
        
        // 下载助手
        $this->load->helper('download');
        // 价格工具
        $this->load->library('pricetool');
        
        // 加载时间工具
        $this->load->library('timetool');
        // 加载缓存，优先使用redis，找不到再使用file，指定业务前缀，以免冲突
        // $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => ENVIRONMENT.':CI_CACHE:'));
        // $this->load->driver('cache', array('adapter' => 'file', 'key_prefix' => ENVIRONMENT.':CI_CACHE:'));
        
        $this->load->helper('pushlog'); // 日志推送辅助函数
        $this->load->helper('lang_format'); // 国际化辅助函数
        $this->load->helper('redis_sequence'); // redis序列辅助函数
        $this->load->helper('static_switch_to_alicdn'); // 云存储资源切换
        $this->load->helper('resp_return'); // 格式化调用返回的结果
                                            
        // 如果传入了调试参数，显示调试信息:
        if ($this->getParam2('debug') == 1) {
            error_reporting(- 1);
            ini_set('display_errors', 1);
        }
    }
    
    // ##############################################################################
    // ############################## 请求参数及应用入口相关 ###########################
    /**
     * 获取参数: 尝试各种传参方式： get, post, data-json
     */
    protected function getParam($key)
    {
        $val = $this->input->post($key);
        if (empty($val)) {
            $val = $this->input->get($key);
        }
        
        // 如果还是查询不出值，则尝试data的方式
        if (empty($val)) {
            
            $data = $this->input->post('data');
            if (empty($data)) {
                $data = $this->input->get('data');
            }
            
            // 如果有data形式的数据，则尝试解读出里面的json
            if (! empty($data)) {
                try {
                    $map = json_decode($data, TRUE);
                    if (array_key_exists($key, $map)) {
                        $val = $map[$key];
                    }
                } catch (Exception $e) {
                    $this->respError(Respcode::CODE_ERROR_FORMATE, 'ERROR:' . $data);
                }
            }
        }
        
        if (empty($val)) {
            $val = '';
        }
        
        return $val;
    }

    /**
     * 获取参数: get, post, 直接返回判断结果，调用方通过isset()函数来判断是否设值
     */
    protected function getParam2($key = null)
    {
        $val = $this->input->post($key);
        if (! isset($val)) {
            $val = $this->input->get($key);
        }
        return $val;
    }
    
    // 获取接口传入的时区，如果没有传入，使用上海时区
    protected function getParamTimezone()
    {
        $timezone = $this->getParam('timezone');
        if (empty($timezone)) {
            $timezone = 'Asia/Shanghai';
        }
        return $timezone;
    }
    
    // 统一入口函数
    public function index($cli_param = '')
    {
        
        // 把cli模式下的cli参数传递进来
        if (is_cli()) {
            define('CLI_PARAM', $cli_param);
        }
        
        // 如果传入了in_china参数，那么加载国内的资源;
        $in_china = $this->getParam2('in_china');
        if (is_numeric($in_china) && ($in_china == 1 || $in_china == 0)) {
            define('IN_CHINA', $in_china);
        } else {
            // 如果没有传入in_china参数，那么根据时区进行判断是否在国内:
            $chinaLocalListLower = [
                "asia/shanghai",
                "asia/chongqing"
            ];
            $paramLocLow = strtolower($this->getParamTimezone());
            if (in_array($paramLocLow, $chinaLocalListLower)) {
                define('IN_CHINA', 1);
            } else {
                define('IN_CHINA', 0);
            }
        }
        
        // 初始化各模型
        $this->initModels();
        if($this->isCheckToken()){
            $this->check_token($this->isCheckToken()); // 检测token
        }else{
            $this->processForUser(false, null, - 1);
        }
        
    }
    
    // 入口处理方法:
    protected function processForUser($isLogin, $me, $token_type)
    {
        $this->respOk(NULL, 'If you see this, it means it is not under implement.');
    }

    private function do_post_api($url, $data)
    {
        return $this->httptool->post($url, $data);
    }
    // /////////////////////////////////////////////////////////////////////////////////
    
    // ##############################################################################
    // ############################# 用户身份授权相关 #################################
    /**
     * 判断是否检测token，默认进行token的检测，子类可以对这个方法进行重写来定义是否需要token访问
     *
     * @return boolean
     */
    protected function isCheckToken()
    {
        return true;
    }

    protected function isLogginApi()
    {
        if (is_cli()) { // 命令行环境下默认不记录日志
            return false;
        }
        return true;
    }

    /**
     * 检测token是否正确
     * $isCheckLogin只是用于判断token是否是必要条件。
     * 这里只要传入了token，都会进行token检查，检查通过后返回用户信息
     */
    private function check_token($isCheckLogin)
    {
        $user = null;
        $token = $this->getParam2('token');
        // 如果接口需要token，然后没有传入token，那么返回错误
        if ($isCheckLogin && empty($token)) {
            $this->respEmptyParam('token');
        }
        
        // 如果token不为空，则进行登录的查询
        if (! empty($token)) {
            $user = $this->user->getUserInfoWithCacheByAppToken($token);
            if ($user == null) {
                $user = $this->user->getUserInfoWithCacheByWebToken($token);
                if ($user == null && $isCheckLogin) { // 如果不需要检查token的接口传入了token，如果token错误，则忽略token
                    $this->respErrorToken();
                }
            }
        }
        
        // if($this->isLogginApi()) {
        // $logParams = $_SERVER;
        // $platform = $this->getParam2('platform');
        // $logParams['user_id'] = $user != null ? $user['id'] : '';
        // $logParams['client_id'] = $this->getParam2('client_id');
        // $logParams['live_id'] = $this->getParam2('live_id');
        // $logParams['platform'] = empty($platform) ? '' : substr($platform, 0, 20);
        // $redis = RedisInstance::getInstance();
        // $redis->lpush(ENVIRONMENT.':LOGGING:api_logging', json_encode($logParams));
        // $redis->expire(ENVIRONMENT.':LOGGING:api_logging', 1800); // 最多保存30分钟，需要计划任务去及时写到存储上去;
        // }
        
        if ($user != null) {
            $user = (object) $user;
            if (IN_CHINA == 1) {
                $user->avatar = static_switch_to_alicdn($user->avatar, 'avatar_600');
            }
            define('USER_ID_LOG', $user->id); // 登陆的用户id，用于记录会话日志
            $this->processForUser(true, $user, $this->user->getTokenType($token));
        } else {
            $this->processForUser(false, null, - 1);
        }
    }
    
    // 校验管理员登陆的token
    protected function getAdminByToken()
    {
        $token = $this->getParam('session');
        if (empty($token)) {
            $this->respEmptyParam('session');
        }
        
        $this->loadModel('manager/admin');
        $admin = $this->admin->getAdminByToken($token);
        if ($admin == null) {
            $this->respError(Respcode::CODE_ERROR_OPTION, 'Error session');
        }
        return $admin;
    }
    
    // /////////////////////////////////////////////////////////////////////////////////
    
    // ##############################################################################
    // ############################### 资源/库 加载 ##################################
    // 加载多语言支持
    private function loadLangs()
    {
        $lang = $this->getParam2('lang');
        $lang = isset($lang) ? strtolower($lang) : 'cn';
        
        // 加载国际化多语言: 默认是英文；
        switch ($lang) {
            case 'en':
                $this->lang->load('api_lang', 'english');
                $this->lang->load('code_lang', 'english');
                $this->lang->load('str_lang', 'english');
                break;
            case 'cn':
            case 'zh-cn':
            case 'zh_cn':
                $this->lang->load('api_lang', 'chinese');
                $this->lang->load('code_lang', 'chinese');
                $this->lang->load('str_lang', 'chinese');
                break;
            case 'tw':
            case 'zh_tw':
            case 'zh-tw':
                $this->lang->load('api_lang', 'taiwan');
                $this->lang->load('code_lang', 'taiwan');
                $this->lang->load('str_lang', 'taiwan');
                break;
            default:
                $this->lang->load('api_lang', 'english');
                $this->lang->load('code_lang', 'english');
                $this->lang->load('str_lang', 'english');
                break;
        }
    }
    // 获取语言，语言的key必须是以字母开始的，不能设置纯数字的key，
    // 尽量设置成以字母开头（纯数字的key获取的时候获取不到;）
    protected function getLang($key)
    {
        // 第二个参数设置为 FALSE 禁用错误日志: 如果语言记录不存在或为空字符串（尽量不要出现这种值为空的语言记录），那么返回空，可以通过empty()函数来判断语言是否存在
        return $this->lang->line($key, false);
    }
    
    // 初始化各模型
    protected function initModels()
    {
        // 是否加载数据库
        if ($this->isLoadDatabase()) {
            $this->check_database();
        }
        $this->load->model('user');
        $this->load->model('trans');
        $this->load->model('caiwu');
        $this->load->model('follow');
        $this->load->model('question');
        $this->load->model('paycache');
        $this->load->model('listenin');
        $this->load->model('unlike');
        $this->load->model('likeit');
        $this->load->model('push_msg');
    }
    
    // 子类控制器可通过这个方法来简化模板的加载语法
    protected function loadModel($name)
    {
        $this->load->model($name);
    }
    
    // 是否加载数据库, 默认需要, 子类可以通过重写这个方法来实现请求不加载数据库
    protected function isLoadDatabase()
    {
        return true;
    }

    private function check_database()
    {
        $this->load->database();
    }
    // /////////////////////////////////////////////////////////////////////////////////
    
    // ##############################################################################
    // ############################# 常用信息获取缓存 #################################
    // 获取缓存前缀: 以URI路径为缓存前缀
    private function getCachePrefix()
    {
        $arr = explode('index.php', current_url());
        if (count($arr) > 1) {
            return str_replace('/', '_', $arr[1]);
        }
        return '';
    }
    
    // 常规设置缓存数据的方法:
    protected function set_cache($key, $second)
    {
        $this->cache->save($this->getCachePrefix() . $key, time(), $second);
    }

    protected function get_cache($key)
    {
        return $this->cache->get($this->getCachePrefix() . $key);
    }
    
    // 用户信息缓存
    // 查询用户出来，若有缓存，则优先使用缓存
    protected function getUserWithCache($user_id)
    {
        $user = $this->user->getUserInfoWithCacheByUserId($user_id);
        // $user = $this->get_cache_user_by_id($user_id);
        // if ($user == null) {
        // $user = $this->user->getById($user_id);
        // if ($user != null) {
        // $this->set_cache_user_all($user);
        // }
        // }
        if ($user != null)
            $user = (object) $user;
        return $user;
    }

    protected function set_cache_user_all($user)
    {
        $prefix = 'QA_USER_';
        // 用户重复使用，不会改变其内容
        $time = 1 * 60 * 30;
        $this->cache->save($prefix . $user->app_token, json_encode($user), $time); // app
        $this->cache->save($prefix . $user->web_token, json_encode($user), $time); // web
        $this->cache->save($prefix . $user->id, json_encode($user), $time); // id
    }

    protected function get_cache_user_by_token($token)
    {
        $user_json = $this->cache->get('QA_USER_' . $token);
        if (! empty($user_json)) {
            return json_decode($user_json);
        }
        return null;
    }

    protected function get_cache_user_by_id($user_id)
    {
        $user_json = $this->cache->get('QA_USER_' . $user_id);
        if (! empty($user_json)) {
            return json_decode($user_json);
        }
        return null;
    }
    
    // 检测请求是否过于频繁: 通过redis中
    protected function checkIfReqTooMuch($key, $second)
    {
        // if ($this->get_cache($key) != null) {
        // $this->respError(Respcode::CODE_ERROR_REQ_TOO_MUCH, $this->getLang('error_request_too_much'));
        // }
        // $this->set_cache($key, $second);
    }
    
    // /////////////////////////////////////////////////////////////////////////////////
    
    // ##############################################################################
    // ############################# 数据返回封装 #####################################
    // 返回成功:
    protected function respOk($data = null, $msg = '')
    {
        $this->resp(Respcode::CODE_OK, $msg, $data);
    }
    
    // Token错误:
    protected function respErrorToken()
    {
        $this->respError(Respcode::CODE_ERROR_TOKEN, $this->getLang('error_token_'));
    }
    
    // 返回网络异错误（一般是数据库）
    protected function respErrorNetwork()
    {
        $this->respError(Respcode::CODE_ERROR_NETWORK, $this->getLang('error_network'));
    }
    
    // 返回错误:
    protected function respError($code = 0, $msg = '', $data = null)
    {
        //pushlog('接口返回错误，code: ' . $code . ', msg: ' . $msg . ', data: ', $data, 3);
        $this->wLog("err", $code."_".$msg);
        $this->resp($code, $msg, $data);
    }
    
    // 写日志
    function wLog($logType, $data)
    {
        $accessPath = dirname(__FILE__) .'/../logs/'. $logType . "/" . date('ym') . '/';
        ! file_exists($accessPath) && mkdir($accessPath, 0777, true);
    
        file_put_contents($accessPath . date('d',time()+28800) . '.txt', date('H:i:s',time()+28800) . '___' . str_replace(array(
            "\n",
            "\r"
        ), '', print_r($data, true) . '___【'.$this->buildRequest2Url().'】___【' . print_r($_REQUEST, true)) . "】\n", FILE_APPEND);
    }
    
    //将post或get请求输出成url
    function buildRequest2Url()
    {
        if (empty($_REQUEST)) {
            return '';
        }
    
        $out = '';
        $sp = '';
        foreach ($_REQUEST as $k => $v) {
            $out .= $sp . $k . "=" . $v;
            $sp = '&';
        }
        return $_SERVER['PATH_INFO'].'?'.$out;
    }
    
    // 参数缺失返回封装:
    protected function respEmptyParam($param_name)
    {
        $msg = lang_format('code_1000001', '缺少参数', $param_name);
        $this->resp(1000001, $msg, null); // 这里code直接填返回码，防止resp再次替换掉$msg字段
    }
    
    // 参数错误返回封装:
    protected function respErrorParam($param_name)
    {
        $msg = lang_format('code_1000002', '参数错误', $param_name);
        $this->resp(1000002, $msg, null); // 这里code直接填返回码，防止resp再次替换掉$msg字段
    }
    
    // 参数格式错误信息返回格式化
    protected function respErrorFormat($name, $min, $max)
    {
        $msg = $this->getLang('error_param_string_format');
        $msg = str_replace('#NAME#', $name, $msg);
        $msg = str_replace('#MIN#', $min, $msg);
        $msg = str_replace('#MAX#', $max, $msg);
        $this->respError(Respcode::CODE_ERROR_FORMATE, $msg);
    }
    
    // 未找到资源: 媒体资源相关
    protected function respErrorResourceNotFound($res_id)
    {
        $msg = $this->getLang('error_res_not_found');
        $a = str_replace('#ID#', $res_id, $msg);
        $this->respError(Respcode::CODE_ERROR_RES_NOT_FOUND, $a);
    }
    
    // 返回结果:
    protected function resp($code, $msg, $data = null)
    {
        if ($code != 0) {
            pushlog('接口返回错误, code: ' . $code . ', msg: ' . $msg, $data, 3);
        }
        
        // 根据code去获取语言文件: 如果获取到的是数组，那么解释成返回code和msg;
        $lanTrans = $this->getLang($code);
        if (is_array($lanTrans) && count($lanTrans) >= 2) {
            $res['c'] = $lanTrans[0];
            $res['m'] = $lanTrans[1];
        } else {
            // 如果没有获取到语言文件，按传入的参数返回
            $res['c'] = is_numeric($code) ? $code : 500;
            $res['m'] = $msg;
        }
        $res['d'] = $data === null ? [] : $data;
        
        // 如果是命令行执行的请求，那么不需要设置header，否则会有warning;
        if (! is_cli()) {
            header("Access-Control-Allow-Origin:*"); // 允许跨域访问
            header('Content-type: application/json'); // 返回json
        }
        
        $json = json_encode($res);
        echo $json;
        
        // 关闭数据库连接
        if (isset($this->db)) {
            $this->db->close();
        }
        $redis = RedisInstance::getInstance();
        $redis->close();
        exit(0); // 退出程序
    }
    
    // ##############################################################################
    // ############################# 媒体资源加载相关 #################################
    /**
     * 创建资源的URL地址
     *
     * @param type $content            
     * @return string
     */
    protected function createResourceURL($path)
    {
        $second = 1 * 60 * 10; // 有效时长，10分钟
        $resource_id = strtolower($this->randomtool->getRandChar(32) . time());
        $this->cache->save('RES_' . $resource_id, $path, $second);
        // 得到当前访问的地址
        
        $url = Consts::QA_SERVER_URL . 'index.php/resource/query?id=' . $resource_id;
        return $url;
    }

    /**
     * 创建免费资源的URL地址
     *
     * @param type $content            
     * @return string
     */
    protected function createFreeResourceURL($path)
    {
        $second = 1 * 60 * 60; // 有效时长，1个小时，只要有人触发，就会继续生效
        $resource_id = strtolower(md5($path));
        $this->cache->save('RES_' . $resource_id, $path, $second);
        // 得到当前访问的地址
        $url = Consts::QA_SERVER_URL . 'index.php/resource/query?id=' . $resource_id;
        return $url;
    }

    /**
     * 根据资源ID，获取资源的绝对路径
     *
     * @param type $resource_id            
     * @return type
     */
    protected function getResourceByResourceId($resource_id)
    {
        return $this->cache->get('RES_' . $resource_id);
    }
    
    // /////////////////////////////////////////////////////////////////////////////////
    
    // ###################################################################################
    // ############################## 日志及其它相关 #######################################
    // 获取日志文件路径，每秒钟的日志都会放到不同的文件；
    protected function getLogPath($prefix)
    {
        // 取出当前地址
        $arr = explode('index.php', current_url());
        // 正确的访问地址
        if (count($arr) > 1) {
            $path = $arr[1];
            if (! empty($path)) {
                $root = Consts::LOGS_PATH . $path . '/' . date('Ymd', time()) . '/';
                // 判断目录是否存在，不存在则创建
                if (! file_exists($root)) {
                    mkdir($root, 0777, true);
                }
                
                if (file_exists($root)) {
                    $file_path = $root . $prefix . '_' . date('YmdHis', time()) . '.log';
                    return $file_path;
                }
            }
        }
    }
    
    // 记录日志到文件
    protected function saveLog($prefix, $msg)
    {
        $path = $this->getLogPath($prefix);
        error_log($msg, 3, $path);
        return $path;
    }
    
    // 标识是否是正式服务器，只有正式服务器有此文件, 现在应该没有使用到这个方法判断了;
    const FILE_REAL_SERVER = '/data/vg02_lv01/wonder_server_data/real_server_not_delete.txt';

    public function isRealServer()
    {
        if (file_exists(MY_Controller::FILE_REAL_SERVER)) {
            return true;
        } else {
            return false;
        }
    }
    
    // 显示最后一条执行的sql:
    protected function showLastSQL()
    {
        return $this->db->last_query();
    }
    // /////////////////////////////////////////////////////////////////////////////////
    
    // 是否正整数>0
    public function isInt($num)
    {
        return ! empty($num) && is_numeric($num) && $num > 0 && strpos($num, '.') === false ? true : false;
    }
    
    // 网上找的hash算法，可以对字符串、数字取模
    public function myHash($str)
    {
        $hash = 0;
        $s = md5($str);
        $seed = 5;
        $len = 32;
        for ($i = 0; $i < $len; $i ++) {
            $hash = ($hash << $seed) + $hash + ord($s{$i});
        }
        return $hash & 0x7FFFFFFF;
    }

    public function __destruct()
    {
        // 关闭数据库连接
        ! empty($this->db) && $this->db->close();
    }
}


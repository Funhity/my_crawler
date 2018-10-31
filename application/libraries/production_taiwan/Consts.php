<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ############################### 台湾正式环境 #####################################

class Consts {

    // S3存储信息: 账号1 - 新加坡区域
    const S3_BUCKET_URL = 'https://s3-ap-southeast-1.amazonaws.com/static.wonder.mobi/';
    const S3_CDN_URL = 'https://d1vvo51t6lqxtd.cloudfront.net';     // 以/open目录为根目录
    const OSS_CDN_URL = 'http://static.dolphinlive.net';            // 阿里云oss CDN域名;

    const S3_BUCKET = 'static.wonder.mobi';
    const S3_CFG = [
        'region'        => 'ap-southeast-1',
        'version'       =>    'latest',
        'credentials'   => [
            'key'       => 'AKIAIDMFCGZRYUJNQEEA',
            'secret'    => '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',
        ],
    ];

    // OSS存储信息：新加坡
    const OSS_CFG = [
        'id'		=>	'LTAIy19qs7E3olIZ',
        'key'		=>	'naAv7n6QtG3osIgPRFeYqk4JRvLoN1',
        'url'		=>	'wonder-live-sp.oss-ap-southeast-1.aliyuncs.com',       // 访问路径
        'bucket'    =>  'wonder-live-sp',
        'host'       =>  'oss-ap-southeast-1.aliyuncs.com'      // api上传路径
    ];

    // 用户默认头像
    const DEFAULT_AVATAR = "https://api.wonder.wiki/static/wendao_logo.png";

    // 应用域名:
    const SITE_ADDR = 'www.wonder.mobi';             // 当前应用环境对应的网站域名
    const API_ADDR = 'api.wonder.mobi';               // 当前应用环境对就的api的域名;
    const ADM_ADDR = 'adm.wonder.mobi';               // 当前应用环境对应的管理后台域名

    // Wechat MP Login:
    const APPID_WECHAT_MP = 'wxe3e6f2e875c60a8d';
    const SECRET_WECHAT_MP = '1bdceb2fa7a9c7838bacd99f438c11d7';

    // Wechat App Login: 问道
    const APPID_WECHAT_OP = 'wx52018314168e581f';
    const SECRET_WECHAT_OP = '4e9f12008ca3b10aaacac89960cfbb84';


    // 台湾远见服务端对接token:
    const TAIWAN_SS_TOKENS = [
        '64e58601d12e09e06f0c25dee90e50c3',
    ];

    /**
     * 服务器地址
     */
    const QA_SERVER_URL = 'https://api.wonder.mobi/';


    // FACEBOOK的APPID
    const APPID_FACEBOOK = '1049086131903346';
    const SECRET_FACEBOOK = '424f656c7eec2327ed9d20f95dffa858';


    // LinkedIn Login INFO:
    const APPID_LINKEDIN = '814ayhcuqyxhfp';
    const SECRET_LINKEDIN = 'KLAFZAZr10AzUQgx';

    // Twitter Login:
    const APPID_TWITTER = 'HAVGRiWo6iPNeEAf9SWXSg';     // Wonder账号
    const SECRET_TWITTER= 'mxCHaIo2BkoJgsOraMfw6qG8TVygTLSK6jz6ifgQdWU';

    /**
     * Paypal创建支付订单地址
     * 正式：https://www.paypal.com/cgi-bin/webscr
     * 测试：https://www.sandbox.paypal.com/cgi-bin/webscr/
     */
    const PAYPAL_CREATE_ORDER_PAY_URL = 'https://www.paypal.com/cgi-bin/webscr/';


    /**
     * Paypal的商户编号
     * 测试：bryanwong-facilitator@live.com
     * 正式：bryanwong@live.com
     */
    const PAYPAL_SHANGHU_EMAIL = 'bryanwong@live.com';//商家的邮件，唯一标识



    /**
     * 苹果支付检测地址
     * 正式：https://buy.itunes.apple.com/verifyReceipt
     * 测试：https://sandbox.itunes.apple.com/verifyReceipt
     */
    const APPLE_PAY_CHECK_URL = 'https://buy.itunes.apple.com/verifyReceipt';

    // 苹果内购app Bundle_ID
    const APPLE_PAY_BUNDLE_ID = 'com.lovecloud.wondering';      // 问道



    /**
     * 资源保存地址
     * 正式：/data/vg02_lv01/wonder_server_data/resources/
     * 测试：/var/www/wonder_server_resources/
     */
    const RES_SAVE_ROOT = '/data/vg02_lv01/wonder_server_data/resources/';

    /**
     * 日志文件目录
     */
    const LOGS_PATH = '/data/vg02_lv01/tw_wonder_server_data/logs/';


    /**
     * 我们赚的比例
     */
    const OUR_EARN_RATE  = 0.4;

    // ###########################################################################
    // 斑马英语阅读配置:
    const TENMONTH_READ = array(
        'start_time'    =>     1495696048,      // 课程的开始时间戳
        'jump_days'     =>      0,              // 中途跳过的天数
    );

    // ===========================================================================
}
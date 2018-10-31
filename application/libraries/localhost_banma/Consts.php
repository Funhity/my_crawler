<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consts
{


    // S3云存储信息: US
//    const S3_BUCKET_URL = 'https://s3-us-west-1.amazonaws.com/res.wonder.wiki/';
//    const S3_BUCKET = 'res.wonder.wiki';
//    const S3_CFG = [
//        'region'        => 'us-west-1',
//        'version'       =>    'latest',
//        'credentials'   => [
//            'key'       => 'AKIAIDMFCGZRYUJNQEEA',
//            'secret'    => '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',
//        ],
//    ];

    // 在所有房间的内部用户id: 
    const ALL_GROUP_USER_ID = array(
        'U2017041410425891442',     // 海升
        'U2017030312135641318',     // Bryan
        'U201703070601453974',      // 彭芬
        'U201706140805036139',      // 苏北辰
        'U2017061503245681029',     // 喵总Laura
        'U2017062610301621424',     //  | 笑草哥哥',
        'U201703150805516032',      //    | boom',
        'U2017031408300841741',     //  | 炜炜炜杰',
        'U2017062102503368159',     //  | 斑马Zebra',
        'U2017030406163449324',     //   | Kevin'
        'U20170518143719548489',    //Jonathan Peng
    );

    // S3存储信息: 新加坡
    const S3_BUCKET_URL = 'https://s3-ap-southeast-1.amazonaws.com/static.wonder.wiki/';
    const S3_CDN_URL = 'https://d3qiassb671423.cloudfront.net';     // 以/open目录为根目录
    const S3_BUCKET = 'static.wonder.wiki';
    const S3_CFG = [
        'region' => 'ap-southeast-1',
        'version' => 'latest',
        'credentials' => [
            'key' => 'AKIAIDMFCGZRYUJNQEEA',
            'secret' => '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',
        ],
    ];

    // OSS存储信息：深圳
    const OSS_CDN_URL = 'http://static.zuiqiangyingyu.cn';            // 阿里云oss CDN域名;
    const OSS_CFG = [
        'id' => 'LTAIdI6ZM1kvx3CF',
        'key' => 'xaU5OQeqSke5sZUA37alW0D0H0KwRm',
        'url' => 'banma-sz.oss-cn-shenzhen.aliyuncs.com',        // 访问路径
        'bucket' => 'banma-sz',
        'host' => 'oss-cn-shenzhen.aliyuncs.com',                 // api上传路径
        'internal' => 'banma-sz.oss-cn-shenzhen-internal.aliyuncs.com',   // 内风访问路径
    ];

    // 阿里云直播信息:
    const ALI_VIDEO_LIVE = [
        'app_name' => 'wonder',
        'domain' => 'live.zuiqiangyingyu.cn',
        'master_key' => 'wonder2048rksabdxdskdo9nm',
        'slave_key' => 'jsdkfj9343fklkjdzoiejwonder194',

    ];

    // 用户默认头像
    const DEFAULT_AVATAR = "http://static.dolphinlive.net/avatar/default/avatar_default.png";


    // 应用域名:
    const SITE_ADDR = 'www.dolphinlive.mobi';             // 当前应用环境对应的网站域名
    const API_ADDR = 'api.dolphinlive.mobi';               // 当前应用环境对就的api的域名;
    const ADM_ADDR = 'adm.dolphinlive.mobi';               // 当前应用环境对应的管理后台域名


    // 测试用户列表:
    const TEST_USER_LIST = array(
        'U20170421103138692',       // 海升
        'U2017042405371342110',     // 梓涛
    );

    // 客服系统token:
    const KF_TOKEN = 'deiJeue9383uHeIW2933hIejdYe08idbBslPiQjjsNBdidIdsaoqQodiIYiwoQdiNkdIoP';

    // 斑马招生办客服转发系统;
    const BM1_KF_LIST = array(
        'oC15VwKX-RLpqj6SgbxRd56cgzow',         // 官海升
        'oC15VwBsAdZz_fPILOhJLIvsDT24',         // Bryan
        'oC15VwHisX1GLkWzrjgAfvFFrhKo',         // Joy
    );

    const BMYY_KF_LIST = array(
        'oCDZpwoLZRPd-cV5wLIb5X5OQhRM',         //Joy
    );


    /**
     * 服务器地址
     */
    const QA_SERVER_URL = 'http://api.dolphinlive.mobi/';


    // FACEBOOK的APPID
    const APPID_FACEBOOK = '1111062858952281';
    const SECRET_FACEBOOK = '81a2dc907b7dd43d0fd30af53fbbd0ab';

    // LinkedIn Login INFO:
    const APPID_LINKEDIN = '8114mt4bup0iqj';
    const SECRET_LINKEDIN = 'FDAfEWf45A53sdfKef513';

    // Twitter Login:
    const APPID_TWITTER = 'HAVGRiWo6iPNeEAf9SWXSg';
    const SECRET_TWITTER = 'mxCHaIo2BkoJgsOraMfw6qG8TVygTLSK6jz6ifgQdWU';


    // Wechat Open Login:
    const APPID_WECHAT_OP = 'wx2bd25b0877c59882';
    const SECRET_WECHAT_OP = 'd756bed1d02a9fe900f5a5b9d71f81b5';

    // Wechat MP Login:
    const APPID_WECHAT_MP = 'wxe3e6f2e875c60a8d';
    const SECRET_WECHAT_MP = '1bdceb2fa7a9c7838bacd99f438c11d7';
    const WECHAT_MP1 = array(
        'name' => 'MP1',
        'name_cn' => 'Wonder知识共享',
        'appid' => 'wxe3e6f2e875c60a8d',
        'secret' => '1bdceb2fa7a9c7838bacd99f438c11d7',
    );


    // 斑马英语公众号
    const WECHAT_BMYY = array(
        'name' => 'BMYY',
        'name_cn' => '斑马英语',
        'appid' => 'wxe73086fdcc78e051',
        'secret' => 'c8c51bf138a45dcef82ebe2edcba1c15',
    );


    // 斑马英语(招生办)公众号
    const WECHAT_BM1 = array(
        'name' => 'BM1',
        'name_cn' => '斑马招生办',
        'appid' => 'wxf673553a2cf0e215',
        'secret' => '5ad4ca0bd9883f56168a3421e8df284e',
    );

    // 斑马英语小程序
    const WECHAT_BMYY_XCX = array(
        'name' => 'BMYYXCX',
        'appid' => 'wx8b4a3d8e1894b68f',
        'secret' => 'c94056577df3e188f3a95ee913709b3b',
    );

    // 斑马英语口语pk
    // reminders 日程提醒模板
    const WECHAT_BMYY_KYPK = array(
        'name' => 'BMYYKYPK',
        'appid' => 'wx6be893197fd323ab',
        'secret' => '0e96766f38a2b700d91315391f30ae5e',
        'templateList' => array(
            'reminders' => 'eZp224Djj82FoyZedcE_Miwxyp9IS8jsHzuSPmZFQBw'
        )
    );

    //一号课堂
    const WECHAT_FIRST_CLASS = array(
        'name' => 'WEAPP_FIRST_CLASS',
        'appid' => 'wx34c667210f657fa7',
        'secret' => '8f7009ff6e1653687b0e6bb3caca8526',
    );

    //美女口语
    const WECHAT_BEAUTY_ORAL_ENGLISH = array(
        'name' => 'WEAPP_BEAUTY_ORAL_ENGLISH',
        'appid' => 'wx86b34211bf48b473',
        'secret' => 'bf7631ce5685b7b249a2205f49d34167',
    );

    //少儿英语
    const WECHAT_SEYY = array(
        'name' => 'SHAO_ER_YING_YU',
        'appid' => 'wxb83bfbbba672379b',
        'secret' => 'fa2db6407d5af4f0c58c98e36d950d7d',
        'templateList' => array(
            'reminders' => 'Zwxf-cscs4wNR3QdYNk5fNsBEmM5Cn7_hmP0OYe2dZQ'
        )
    );

    //成语猜猜猜
    const WECHAT_CYCCC = array(
        'name' => 'CHEN_YU_CAI_CAI_CAI',
        'appid' => 'wx04f7f967a6064c17',
        'secret' => '978167bb3bce1cf608cbc8226d580330',
        'signature_key' => '$%^##$%^&*!!key'
    );

    //画画猜猜
    const WECHAT_HHCC = array(
        'name' => 'HUA_HUA_CAI_CAI',
        'appid' => 'wxf2a06e4b30d4ad83',
        'secret' => '758b003ddd4ee4a0ebff9986e8e8c827',
        'templateList' => array(
            'reminders' => 'nSxb_miEiVtX2By16BlNUV98m6k0mscEzJR7pv_Mc3o',
            //新作提醒
            'new_recommend' => '27fmDNcQehrgUKFWcGCJY53133vcdURdnT4FrJnmWhY',
        )
    );

    //包你答
    // const WECHAT_VOTE = array( 
    //     'name' => 'WEAPP_VOTE',
    //     'appid' => 'wxf5ead0b4d49a0d65',
    //     'secret' => '6203bc0607c0f9275d8b2f6476408400',
    // );

    const WECHAT_VOTE = array(
        'name' => 'WEAPP_VOTE',
        'appid' => 'wxc571100f202d4379',
        'secret' => '458ad553803ee196d982994665753df1',
    );

    //单词接龙
    const WECHAT_DCJL = array(
        'name' => 'DAN_CI_KIE_LONG',
        'appid' => 'wx29ee8ce916dc06e9',
        'secret' => 'd43953c545de1e3b19202c23f086776d',
    );

    //紅包名片
    const WECHAT_BUSINESS_CARD = array(
        'name' => 'WEAPP_BUSINESS_CARD',
        'appid' => 'wxc2c83d427061d6bd',
        'secret' => 'cb22f31edcba65a57f0f10ec51bf780a',
    );

    //你画我萌 暂时拿来做每日交易，商户号需连续30天正常交易
    const WECHAT_TEMP = array(
        'name' => 'WEAPP_TEMP',
        'appid' => 'wx5901644357e13fbd',
        'secret' => '3c421b2b65dad9f10bf8b15eef3b8917',
    );

    //成语研究所
    const WECHAT_CYYJS = array(
        'name' => 'WEAPP_CYYJS',
        'appid' => 'wxfe8d950f3a427927',
        'secret' => '992d25284f4eb6c94edb45466ace93e1',
    );

    //成语大师
    const WECHAT_IDIOM_MASTER = array(
        'original_id'=>"gh_1b5009f16cf0",
        'name' => 'WEAPP_IDIOM_MASTER',
        'appid' => 'wxbe324c4f7f214977',
        'secret' => 'a70f277ce86c35f423e9aa2c1250cb6c',
        'token'=>"Y7HygrrQeD1SmeauPLeHV72jPyxbbMwU"
    );


    //成语大师 - 小游戏
    // const WEGAME_IDIOM_MASTER = array(
    //         'name' => 'WEGAME_IDIOM_MASTER',
    //         'appid' => 'wxb5de2e873b74455e',
    //         'secret' => '62f4def5decf05b044e11b9f2bf93acd',
    // );

    const WEGAME_IDIOM_MASTER = array(
        'name' => 'WEGAME_IDIOM_MASTER',
        'appid' => 'wx7af39a1f0c4bc6ea',
        'secret' => 'e00c1f4a8f27237c4e9bc85314db1c2a',
    );

    //开心消成语
    const WECHAT_HAPPY_IDIOM = array(
        'name' => 'WECHAT_HAPPY_IDIOM',
        'appid' => 'wx9a73e5e1618b1d87',
        'secret' => '72550b1ea9bd3ef4ae94e7301a8a62c6',
    );

    //猜图达人
    const WECHAT_GUESS_PIC = array(
        'name' => 'WECHAT_GUESS_PIC',
        'appid' => 'wx09cfec9b5e5d4884',
        'secret' => 'e484fdb0941f7258dd26db9657ad4937',
        'token'=>"Y7HygrrQeD1SmeauPLeHV72jPyxbbMwU"
    );

    //答题达人
    const WECHAT_ANSWER_KING = array(
        'name' => 'WECHAT_ANSWER_KING',
        'appid' => 'wxfdf8a4c61e486dc2',
        'secret' => '8103531ae58a6fadb09a089dfb8c009e',
    );
    //aa 见缝插针
    const WECHAT_AA = array(
        'name' => 'WECHAT_AA',
        'appid' => 'wxfb7c0d8f12fe9653',
        'secret' => 'b6b3dd1c66b99dfdc0b2e401169ac791',
    );
    //ss_platform 	 游戏大挑战
    const WECHAT_SS = array(
        'name' => 'WECHAT_SS',
        'appid' => 'wxe1b1e619b7f109f3',
        'secret' => '162b2fac66d8c5255e0d588c2f5aeb19',
    );
    // 飞刀手pk
    const WECHAT_FEI_DAO_SHOU = array(
        'name' => 'WECHAT_FEI_DAO_SHOU',
        'appid' => 'wx99545f982dc06dfc',
        'secret' => '30a84209ee3118cae0c92a9793c948d6',
    );
    //猫咪爱情大作战
    const WECHAT_MAO_MI_AI_QING = array(
        'name' => 'WECHAT_MAO_MI_AI_QING',
        'appid' => 'wx76ce7ee5a5088c50',
        'secret' => '7e2b14e4765de741fe010c1753261442',
    );
    //IQ挑战大会
    const WECHAT_IQ_TIAO_ZHAN = array(
        'name' => 'WECHAT_IQ_TIAO_ZHAN',
        'appid' => 'wx4d05678095894913',
        'secret' => 'c6c9b97ac9b1fbbd748fd2a61d9ca511',
    );
    // 棍子萌鸭pk
    const WECHAT_GUN_ZI_PK = array(
        'name' => 'WECHAT_FEI_DAO_SHOU',
        'appid' => 'wxda3e01967474270f',
        'secret' => '577579611fa74fc0f8b7513b23548eb5',
    );
    const WECHAT_XIAO_XIAO_YU_TANG = array(
        'name' => 'WECHAT_XIAO_XIAO_YU_TANG',
        'appid' => 'wxb2913c14616f88c2',
        'secret' => 'b6099c39fef5ef93ff8e2813192f8cb',
    );
    const WECHAT_JI_PING_FEI_CHE = array(
        'name' => 'WECHAT_JI_PING_FEI_CHE',
        'appid' => 'wx8b18e7dd829b73ff',
        'secret' => 'b836eeea61ffc7eb35ac4e7d3883f699',
    );
    //flappy_bird
    const WECHAT_FB = array(
        'name' => 'WECHAT_FB',
        'appid' => 'wxc399ace77e02be7f',
        'secret' => 'b0a97ac8707d0b31806fea4408f444ce',
    );
    // 双双游戏  小游戏  ss
    const WECHAT_FB_SS = array(
        'name' => 'WECHAT_FB',
        'appid' => 'wx77a051f7a320796a',
        'secret' => 'f8556740fc990f95d6e0900c8f430618',
    );
//双双游戏 微信开放平台
    const WECHAT_SSG_KAI_FANG_PING_TAI = array(
        'name' => 'WECHAT_SSG_KAI_FANG_PING_TAI',
        'appid' => 'wxe6a132dbf90dbf91',
        'secret' => '5b11e2966021d008b26e4921cae50665',
    );
    //萌萌水族箱
    const WECHAT_MENG_MENG_SHUI_ZU_XIANG= array(
        'name' => 'MENG_MENG_SHUI_ZU_XIANG',
        'appid' => 'wx6c1ccbfaab535518',
        'secret' => 'a0adc1b3aaacf98404c94bec9caef720',
    );
    //打怪萌娘
    const WECHAT_DA_GUAI_MENG_NIANG= array(
        'name' => 'WECHAT_DA_GUAI_MENG_NIANG',
        'appid' => 'wx8efdadc3fbb7c6a3',
        'secret' => '73d89fe83b5d536f91c3d6e9774f171c',
    );
    //猜歌
    const WECHAT_GUESS_SONG = array(
        'name' => 'GUESS_SONG',
        'appid' => 'wxcc640ae11bf31aec',
        'secret' => '6b92f49bce0c5802b935cad30317d4ca',

        // 微信支付通知回调接口
        'pay_notify_url' => 'https://api.zuiqiangyingyu.net/index.php/api/guess_v2/pay/Notify',
    );

    //猜歌答题王
    const WECHAT_GUESS_SONG_KING = array(
        'name' => 'GUESS_SONG_KING',
        'appid' => 'wx33f4f4bf0be97810',
        'secret' => '53b52393da0a3e6e637df349bdfc3bf9',

        // 微信支付通知回调接口
        'pay_notify_url' => 'https://api.zuiqiangyingyu.net/index.php/api/guess_v2/pay/maja/Notify',
    );

    //明星赢现金
    const WECHAT_GUESS_SONG_PIC = array(
        'name' => 'GUESS_SONG_PIC',
        'appid' => 'wx95e1fd309fd2d844',
        'secret' => '7d8213e761d2689f01069ab724d06ac2',

        // 微信支付通知回调接口
        'pay_notify_url' => 'https://api.zuiqiangyingyu.net/index.php/api/guess_v2/pay/maja/Notify5',
    );

    //歌神赢现金
    const WECHAT_GUESS_SONG_GOD = array(
        'name' => 'GUESS_SONG_GOD',
        'appid' => 'wx2297b4f5b34fe90d',
        'secret' => 'bbdbbeb2edd23a7151254987138d026e',

        // 微信支付通知回调接口
        'pay_notify_url' => 'https://api.zuiqiangyingyu.net/index.php/api/guess_v2/pay/maja/Notify6',
    );

    //猜歌欢乐斗
    const WECHAT_GUESS_SONG_HAPPY = array(
        'name' => 'GUESS_SONG_HAPPY',
        'appid' => 'wxcad381e685a110cb',
        'secret' => 'bbdbbeb2edd23a7151254987138d026e',

        // 微信支付通知回调接口
        'pay_notify_url' => 'https://api.zuiqiangyingyu.net/index.php/api/guess_v2/pay/maja/Notify7',
    );



    //唱唱猜猜
    const WECHAT_SING_SONG = array(
        'name' => 'SING_SONG',
        'appid' => 'wx439eef3ad884cc7b',
        'secret' => '7c28673b4ca6378d80d070b124df0495',
    );

    //拼图pk
    const WECHAT_JIGSAW = array(
        'name' => 'JIGSAW',
        'appid' => 'wx9b4cf7353a05f821',
        'secret' => '8f76620c7ffa957c80d51347cda8fc14',
    );

    //别踩白块
    const WECHAT_BW = array(
        'name' => 'BW',
        'appid' => 'wxc2c83d427061d6bd',
        'secret' => 'cb22f31edcba65a57f0f10ec51bf780a',
    );

    //答题 加减
    const WECHAT_DA_TI_JIA_JIAN = array(
        'name' => 'WECHAT_DA_TI_JIA_JIAN',
        'appid' => 'wx92a2b2fb77ab431c',
        'secret' => '2d5ee6c7c4767345e4b993c403bb7a20',
    );
    //答题 汉字
    const WECHAT_DA_TI_HAN_ZI = array(
        'name' => 'WECHAT_DA_TI_HAN_ZI',
        'appid' => 'wx346175d1c6702990',
        'secret' => 'ce4a4844e6f988502509864b97ffa8b7',
    );
    //答题 拼音
    const WECHAT_DA_TI_PIN_YIN = array(
        'name' => 'WECHAT_DA_TI_PIN_YIN',
        'appid' => 'wxc48ae15e1f683b30',
        'secret' => 'f0c01263062fad76e0b6013b1463ba1e',
    );

    //答题 最强猜颜色
    const WECHAT_DA_TI_COLOR = array(
        'name' => 'WECHAT_DA_TI_COLOR',
        'appid' => 'wx1a9625b8e910dc27',
        'secret' => '73aa74a9fbc460997528c1cdb7dd02b8',
    );
    //答题 明星赢现金
    const WECHAT_DA_TI_STAR = array(
        'name' => 'WECHAT_DA_TI_STAR',
        'appid' => 'wxb9d0772682c65ce7',
        'secret' => 'fe3e5e2ab96fc8484f1b2058d5e3a5cf',
    );


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
    const APPLE_PAY_BUNDLE_ID = 'com.lovecloud.wonder';      // Wonder


    /**
     * 资源保存地址
     * 正式：/data/vg02_lv01/wonder_server_data/resources/
     * 测试：/var/www/wonder_server_resources/
     */
    const RES_SAVE_ROOT = '/data/vg02_lv01/wonder_server_data/resources/';

    /**
     * 日志文件目录
     * 正式： /data/vg02_lv01/wonder_server_data/logs/
     * 测试： /var/www/html/wonder_server_logs
     */
    const LOGS_PATH = '/data/vg02_lv01/wonder_server_data/logs/';


    /**
     * 我们赚的比例
     */
    const OUR_EARN_RATE = 0.4;


    // ###########################################################################
    // 斑马英语阅读配置:
    const TENMONTH_READ = array(
        'activity' => '10month_reading',  // 当前属于哪个活动
        'start_time' => 1503194324,         // 课程的开始时间戳
        'jump_days' => 0,                  // 中途跳过的天数
    );

    const CLASS_LIST = array(
        '20170002' => '1501',
        '20170010' => '1502',
        '20170012' => '1503',
        '20170056' => '1504',
    );

    // ===========================================================================


}

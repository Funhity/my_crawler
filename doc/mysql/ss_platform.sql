
--  游戏大挑战 begin
-- 游戏配置
DROP TABLE IF EXISTS `ss_wx_setting`;
CREATE TABLE `ss_wx_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `appid` varchar(36) NOT NULL DEFAULT '' COMMENT 'appid',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '展示标题',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '小游戏或小程序路径 ',
  `stastics_name` varchar(32) NOT NULL DEFAULT  '' COMMENT '统计英文名',
  `extra` varchar(255) NOT NULL DEFAULT  '' COMMENT '附加数据  json格式',
  `src` varchar(255) NOT NULL DEFAULT '' ,
  `qrcode_src` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序二维码',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序 ' ,
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '1、banner 2、好友在玩 3、热门游戏' ,
  `min_person_number` int(11) NOT NULL DEFAULT '0' COMMENT '最小玩家人数 ' ,
  `data_version` varchar(10) NOT NULL DEFAULT '' COMMENT '数据版本 ',
  `desc` varchar(20) NOT NULL DEFAULT '' COMMENT '描述',
  `is_random` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是不是随机游戏 1是 2否 ',
  `has_border` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否有边框 1是 2否 ',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 用户表
DROP TABLE IF EXISTS `ss_user`;
CREATE TABLE `ss_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `unionid` varchar(50) NOT NULL DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` ),
  KEY `idx_unionid` (`unionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`openid`);
-- 抽奖历史
DROP TABLE IF EXISTS `ss_lottery_history`;
CREATE TABLE `ss_lottery_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `title` varchar(50) NOT NULL COMMENT '抽奖标题',
  `desc` varchar(50) NOT NULL COMMENT '抽奖描述',
  `l_status` varchar(10) NOT NULL DEFAULT '0' COMMENT '抽奖状态',
  `type` varchar(20) NOT NULL DEFAULT '0' COMMENT '抽奖类型',
  `item_number` int(11) NOT NULL DEFAULT '0' COMMENT '道具数量',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`openid`);
-- 抽奖历史  新表
DROP TABLE IF EXISTS `ss_lottery_history_new`;
CREATE TABLE `ss_lottery_history_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `title` varchar(50) NOT NULL COMMENT '抽奖标题',
  `desc` varchar(50) NOT NULL COMMENT '抽奖描述',
  `l_status` varchar(10) NOT NULL DEFAULT '0' COMMENT '抽奖状态',
  `type` varchar(20) NOT NULL DEFAULT '0' COMMENT '抽奖类型',
  `item_number` int(11) NOT NULL DEFAULT '0' COMMENT '道具数量',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`openid`);
-- 邀请记录
DROP TABLE IF EXISTS `ss_invit_friend`;
CREATE TABLE `ss_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `share_openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `is_new_user`  tinyint(1) unsigned  NOT NULL DEFAULT '2'  COMMENT '邀请的是否新用户 1是 2不是',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
   UNIQUE KEY `u_idx_openid_sopenid` (`openid`,`share_openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- 流量充值
DROP TABLE IF EXISTS `ss_meal_order`;
CREATE TABLE `ss_meal_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `phone`  varchar(15) NOT NULL DEFAULT '0' COMMENT '充值的手机',
  `third_phone`  varchar(15) NOT NULL DEFAULT '0' COMMENT '第三方回调返回的手机',
  `third_order_no` varchar(64) NOT NULL DEFAULT '0' COMMENT ' 第三方的订单号',
  `order_no`  varchar(32) NOT NULL DEFAULT '0' COMMENT '订单号',
  `meal`  varchar(10) NOT NULL DEFAULT '0' COMMENT '流量',
  `mealtag`  varchar(15) NOT NULL DEFAULT '0' COMMENT '流量包标识，提交流量包需要提供此参数',
  `discount`  varchar(15) NOT NULL DEFAULT '0' COMMENT '限价折扣',
  `salemindiscount`  varchar(5) NOT NULL DEFAULT '0' COMMENT '限价折扣，0 为不限价，其他为限价',
  `mealprice`  varchar(15) NOT NULL DEFAULT '0' COMMENT '扣费, mealprice=discount*standardprice;',
  `price`   varchar(15) NOT NULL DEFAULT '0' COMMENT '实际消费金额',
  `saleminprice`  varchar(15) NOT NULL DEFAULT '0' COMMENT '流量套餐编号',
  `standardprice`  varchar(15) NOT NULL DEFAULT '0' COMMENT ' ',
  `forbitecommerceflag`  varchar(5) NOT NULL DEFAULT '0' COMMENT '禁止电商出售，1，禁止，0，不禁止',
  `mealtimespan`  varchar(5) NOT NULL DEFAULT '0' COMMENT '限期，0 为自然月，其他为天数，特殊双方协',
  `networktype`  varchar(5) NOT NULL DEFAULT '0' COMMENT '0：2/3G, 1,：2/3/4G',
  `areatype`  varchar(5) NOT NULL DEFAULT '0' COMMENT '适用范围,0 全国，1，省内(全国漫游),2,省内(省内漫游)',
  `provider`  varchar(15) NOT NULL DEFAULT '0' COMMENT '流量提供商',
  `providertype`  varchar(5) NOT NULL DEFAULT '0' COMMENT '流量提供商 1 移动，2 联通，4 电信',
  `status` int(11) NOT NULL DEFAULT 1  COMMENT '订单状态  1未领取 2充流量订单（第三方）提交成功 3充流量订单（第三方）提交失败 4充流量成功（第三方回调显示成功） 5充流量失败（第三方回调显示失败）',
  `third_err_msg`  varchar(1000) NOT NULL DEFAULT '0' COMMENT '第三方回调 返回的错误信息',
  `source` int(11) NOT NULL  DEFAULT '0' COMMENT '1：抽奖',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- 金额流水表
DROP TABLE IF EXISTS `ss_money_transaction`;
CREATE TABLE `ss_money_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `app_id` varchar(22) NOT NULL DEFAULT '' COMMENT 'app_id',
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT 'openid',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金额单位分 ',
  `source` varchar(20) NOT NULL DEFAULT '' COMMENT ' 来源 ' ,
  `trans_no`  varchar(32) NOT NULL DEFAULT '0' COMMENT '交易号',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `submit_time` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `idx_appid_openid_seq` (`app_id`,`openid`,`trans_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`app_id`);

-- 第三方用户表
DROP TABLE IF EXISTS `ss_third_user`;
CREATE TABLE `ss_third_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `app_id` varchar(22) NOT NULL DEFAULT '' COMMENT 'app_id',
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT 'openid',
  `ss_openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的openid',
  `balance` int(11) NOT NULL DEFAULT 0  COMMENT '余额',
  `total_money` int(11) NOT NULL DEFAULT 0  COMMENT '单位 分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_appid_openid` (`app_id`,`openid` ),
  KEY `idx_ssopenid` (`ss_openid`),
  KEY `idx_appid_ssopenid` (`app_id`,`ss_openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`app_id`);

-- 小程序接入游戏大挑战的配置表 小程序信息
DROP TABLE IF EXISTS `ss_third_app_setting`;
CREATE TABLE `ss_third_app_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `appid` varchar(36) NOT NULL DEFAULT '' COMMENT 'appid',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '展示标题',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '小游戏或小程序路径 ',
  `stastics_name` varchar(32) NOT NULL DEFAULT  '' COMMENT '统计英文名',
  `extra` varchar(255) NOT NULL DEFAULT  '' COMMENT '附加数据  json格式',
  `src` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序 ' ,
  `app_type` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '应用类型 1小程序 2小游戏 ' ,
  `min_withdraw` int(11) NOT NULL DEFAULT '0' COMMENT '最小提现金额 单位 分 ' ,
  `min_withdraw_2` int(11) NOT NULL DEFAULT '0' COMMENT '再次最小提现金额 单位 分 ' ,
  `max_withdraw` int(11) NOT NULL DEFAULT '0' COMMENT '最大提现金额 单位 分 ' ,
  `max_person_number` int(11) NOT NULL DEFAULT '0' COMMENT '最大提现人数 0表示不限人数 ' ,
  `version` varchar(10) NOT NULL DEFAULT 'release' COMMENT '应用版本  develop（开发版），trial（体验版），release（正式版）',
  `wd_succ_callback_url` varchar(500) NOT NULL DEFAULT  '' COMMENT  '提现成功回调url',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 每日 发现 app 表
DROP TABLE IF EXISTS `ss_discover_app`;
CREATE TABLE `ss_discover_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `appid` varchar(36) NOT NULL DEFAULT '' COMMENT 'appid',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '展示标题',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '小游戏或小程序路径 ',
  `stastics_name` varchar(32) NOT NULL DEFAULT  '' COMMENT '统计英文名',
  `extra` varchar(255) NOT NULL DEFAULT  '' COMMENT '附加数据  json格式',
  `src` varchar(255) NOT NULL DEFAULT '',
  `bg_src` varchar(255) NOT NULL DEFAULT '',
  `qrcode_src` varchar(255) NOT NULL DEFAULT '' COMMENT '小程序二维码',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序 ( 倒序)',
  `desc` varchar(20) NOT NULL DEFAULT '' COMMENT '描述',
  `version` varchar(10) NOT NULL DEFAULT 'release' COMMENT '应用版本  develop（开发版），trial（体验版），release（正式版）',
  `active_timestamp` int(11) NOT NULL DEFAULT '0',
  `is_replace` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是不是替换游戏 1是 2否 ',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 福利页 表
DROP TABLE IF EXISTS `ss_welfare_app`;
CREATE TABLE `ss_welfare_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type` varchar(15) NOT NULL DEFAULT '' COMMENT 'page：小程序的页面 web_page：网页页面',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '展示标题',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '小游戏或小程序路径 ',
  `stastics_name` varchar(32) NOT NULL DEFAULT  '' COMMENT '统计英文名',
  `src` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序 ( 倒序)',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 提现订单
DROP TABLE IF EXISTS `ss_withdraw_order`;
CREATE TABLE `ss_withdraw_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `appid` varchar(22) NOT NULL DEFAULT '' COMMENT '第三方 app_id',
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '第三方 openid',
  `ss_openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的openid',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金额单位分 ',
  `source` varchar(20) NOT NULL DEFAULT '' COMMENT ' 来源 ' ,
  `trans_no`  varchar(32) NOT NULL DEFAULT '0' COMMENT '交易号',
  `wx_payment_no`  varchar(32) NOT NULL DEFAULT '0' COMMENT '微信订单号',
  `wx_payment_time`  varchar(25) NOT NULL DEFAULT '0' COMMENT '微信支付成功时间',
  `wx_err_code`  varchar(32) NOT NULL DEFAULT '0' COMMENT '微信错误代码',
  `wx_err_code_des`  varchar(128) NOT NULL DEFAULT '0' COMMENT '微信错误代码描述',
  `wx_return_json` text COMMENT '微信返回信息',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `reject_res`  varchar(100) NOT NULL DEFAULT '0' COMMENT '拒绝理由',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:未付款 2：已向微信申请付款 3：微信付款成功 4：微信付款失败 5:待审核 6：拒绝提款',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT ' 更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_appid_openid_trans_no` (`appid`,`openid`,`trans_no`),
  KEY `idx_ss_openid` (`ss_openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`appid`);
-- 提现操作 log
DROP TABLE IF EXISTS `ss_withdraw_log`;
CREATE TABLE `ss_withdraw_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `appid` varchar(22) NOT NULL DEFAULT '' COMMENT '第三方 app_id',
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '第三方 openid',
  `ss_openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的openid',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金额单位分 ',
  `source` varchar(20) NOT NULL DEFAULT '' COMMENT ' 来源 ' ,
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT ' ip ' ,
  `content`  text NOT NULL  COMMENT '内容',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `err_type`  varchar(20) NOT NULL DEFAULT '0' COMMENT '错误类别',
  `err_code`  varchar(10) NOT NULL DEFAULT '0' COMMENT '错误代码',
  `err_code_des`  varchar(128) NOT NULL DEFAULT '0' COMMENT '错误代码描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`appid`);
-- 每日任务
DROP TABLE IF EXISTS `ss_day_task`;
CREATE TABLE `ss_day_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的 openid',
  `item_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具或机会等',
  `item_type` varchar(20)  NOT NULL DEFAULT '0' COMMENT '道具类型 gold:金币',
  `day` int(11) NOT NULL DEFAULT '0' COMMENT '第几天',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `sub_item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `is_get_addition` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 已获得附加道具，如红包等 0没有  ',
  `addition_item_value` int(11) NOT NULL DEFAULT 0 COMMENT ' 附加道具数量 如果是金额单位为分 ',
  `addition_item_type` varchar(20)  NOT NULL DEFAULT '0' COMMENT '附加道具类型 money:金额',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1 已领取奖励',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_openid_daystr` (`openid`,`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);


-- 用户投诉
DROP TABLE IF EXISTS `ss_user_complaint`;
CREATE TABLE `ss_user_complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的 openid',
  `page` varchar(20) NOT NULL DEFAULT 0 COMMENT '来源有页面 my：我的 withdraw:提现',
  `content` varchar(20) NOT NULL DEFAULT 0 COMMENT '内容',
  `content_type` varchar(20) NOT NULL DEFAULT 0 COMMENT '内容类型',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1  ',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_openid_daystr` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);

-- 用户道具表
DROP TABLE IF EXISTS `ss_item`;
CREATE TABLE `ss_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的 openid',
  `item_type` int(11) NOT NULL  DEFAULT 0 COMMENT '道具类型  1金币 ',
  `remain_item` int(11) NOT NULL DEFAULT 0 COMMENT '剩余道具',
  `total_item` int(11) NOT NULL DEFAULT 0 COMMENT '总道具',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 有效 2失效 3兑换失败',
  `exchange_money` int(11) NOT NULL  DEFAULT 0 COMMENT '兑换金额 单位分 ',
  `exchange_res` varchar(128) NOT NULL DEFAULT '' COMMENT '兑换结果',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT ' 更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid_itemtype_dstr` (`openid`,`item_type`,`date_str` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`openid`);
-- 用户道具流水表
DROP TABLE IF EXISTS `ss_item_transaction`;
CREATE TABLE `ss_item_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的 openid',
  `item_type` int(11) NOT NULL  DEFAULT 0 COMMENT '道具类型  1金币',
  `value` int(11) NOT NULL  DEFAULT 0 COMMENT '使用或获得的道具 单位分',
  `item_source` int(11) NOT NULL DEFAULT 0 COMMENT '道具来源 1:每日签到',
  `sub_item_source` int(11) NOT NULL DEFAULT 0 COMMENT '详细的道具来源 1:每日签到',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid_datestr` (`openid`,`date_str` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`openid`);


-- 提现回调结果记录表
DROP TABLE IF EXISTS `ss_withdraw_callback_log`;
CREATE TABLE `ss_withdraw_callback_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `appid` varchar(22) NOT NULL DEFAULT '' COMMENT '第三方 app_id',
  `openid` varchar(32) NOT NULL DEFAULT '' COMMENT '第三方 openid',
  `ss_openid` varchar(32) NOT NULL DEFAULT '' COMMENT '游戏大挑战的openid',
  `trans_no`  varchar(32) NOT NULL DEFAULT '0' COMMENT '交易号',
  `return_res`  varchar(200) NOT NULL DEFAULT '0' COMMENT '返回结果',
  `data_json`  varchar(500)  NOT NULL  DEFAULT '0'  COMMENT '参数',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1 回调成功 0失败 ',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`appid`);


-- 金币兑换金钱统计表
DROP TABLE IF EXISTS `ss_gd_exchange_stat_log`;
CREATE TABLE `ss_gd_exchange_stat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `total_gold_coin` int(11) NOT NULL DEFAULT '0' COMMENT '总参与兑换的金币',
  `total_person` int(11) NOT NULL DEFAULT '0' COMMENT '总人数',
  `per_person_money` int(11) NOT NULL DEFAULT '0' COMMENT '人均金额 单位分',
  `total_money` int(11) NOT NULL DEFAULT '0' COMMENT '总发放金额 单位分',
  `min_person_money` int(11) NOT NULL DEFAULT '0' COMMENT '最小金额 单位分',
  `max_person_money` int(11) NOT NULL DEFAULT '0' COMMENT '最大金额 单位分',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `per_gold_coin` int(11) NOT NULL DEFAULT '0' COMMENT '每份per_gold_coin可兑换per_money  ',
  `per_money` int(11) NOT NULL DEFAULT '0' COMMENT '每份per_gold_coin可兑换per_money 单位分',
  `total_success` int(11) NOT NULL DEFAULT '0' COMMENT '兑换成功人数',
  `total_fail` int(11) NOT NULL DEFAULT '0' COMMENT '兑换失败人数',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1  ',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;



-- 游戏大挑战版本配置
DROP TABLE IF EXISTS `ss_version`;
CREATE TABLE `ss_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `version` varchar(32) NOT NULL DEFAULT '' COMMENT 'version',
  `status` tinyint(0) NOT NULL DEFAULT 1 COMMENT '1 有效 其他为无效',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- form id
DROP TABLE IF EXISTS `ss_form_id`;
CREATE TABLE `ss_form_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid_formid` (`openid`,`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);


-- 待推送列表
DROP TABLE IF EXISTS `ss_wait_push_msg`;
CREATE TABLE `ss_wait_push_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 gold_exchange:金币兑换金钱 sign_notice:签到提醒',
  `push_res_json` text not null COMMENT '微信返回的发送结果json',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未推送 2推送成功 3推送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` (`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);


-- 微信模板消息推送统计表
DROP TABLE IF EXISTS `ss_push_wx_msg_stati`;
CREATE TABLE `ss_push_wx_msg_stati` (
  `id` int(11) NOT NULL AUTO_INCREMENT   ,
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 gold_exchange:金币兑换金钱 sign_notice:签到提醒',
  `push_type_cn` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送名称 gold_exchange:金币兑换金钱 sign_notice:签到提醒',
  `push_total`  int(11) NOT NULL DEFAULT '0' COMMENT '总共需要推送',
  `no_push`  int(11) NOT NULL DEFAULT '0' COMMENT '未推送',
  `push_succ`  int(11) NOT NULL DEFAULT '0' COMMENT '推送成功',
  `push_fail`  int(11) NOT NULL DEFAULT '0'  COMMENT '推送失败',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1 2',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str_pushtype` ( `date_str`,`push_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- 每日报表
DROP TABLE IF EXISTS `ss_day_stat`;
CREATE TABLE `ss_day_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT   ,
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `total_person`  int(11) NOT NULL DEFAULT '0' COMMENT '总访问人数',
  `total_person_gc`  int(11) NOT NULL DEFAULT '0' COMMENT '领取金币用户',
  `total_gc`  int(11) NOT NULL DEFAULT '0' COMMENT '领取金币总额',
  `per_person_gc`  int(11) NOT NULL DEFAULT '0' COMMENT '人均领取金币',
  `total_sign`  int(11) NOT NULL DEFAULT '0' COMMENT '签到次数',
  `total_lottery_person`  int(11) NOT NULL DEFAULT '0' COMMENT '任务转盘人数',
  `total_lottery`  int(11) NOT NULL DEFAULT '0' COMMENT '任务转盘次数',
  `total_money`  int(11) NOT NULL DEFAULT '0' COMMENT '目前账户总额',
  `total_withdraw`  int(11) NOT NULL DEFAULT '0' COMMENT '提现次数',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1 2',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` ( `date_str` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;

-- 每日提现统计
DROP TABLE IF EXISTS `ss_withdraw_stat_day`;
CREATE TABLE `ss_withdraw_stat_day` (
  `id` int(11) NOT NULL AUTO_INCREMENT   ,
  `appid` varchar(22) NOT NULL DEFAULT '' COMMENT '第三方 app_id',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',

  `all_count`  int(11) NOT NULL DEFAULT '0' COMMENT '提现成功总次数',
  `all_sum`  int(11) NOT NULL DEFAULT '0' COMMENT '提现成功总额',
  `all_person_count`  int(11) NOT NULL DEFAULT '0' COMMENT '提现成功总人数',

  `all_fail_count`  int(11) NOT NULL DEFAULT '0' COMMENT '提现失败总次数',
  `all_fail_sum`  int(11) NOT NULL DEFAULT '0' COMMENT '提现失败总人数',
  `all_fail_person_count`  int(11) NOT NULL DEFAULT '0' COMMENT '提现失败总额',

  `day_count`  int(11) NOT NULL DEFAULT '0' COMMENT '当天提现成功总次数',
  `day_sum`  int(11) NOT NULL DEFAULT '0' COMMENT '当天提现成功总人数',
  `day_person_count`  int(11) NOT NULL DEFAULT '0' COMMENT '当天提现成功总额',

  `day_fail_count`  int(11) NOT NULL DEFAULT '0' COMMENT '当天提现总失败次数',
  `day_fail_sum`  int(11) NOT NULL DEFAULT '0' COMMENT '当天提现总失败金额额',
  `day_fail_person_count`  int(11) NOT NULL DEFAULT '0' COMMENT '当天提现总失败人数',

  `day_approval_count`  int(11) NOT NULL DEFAULT '0' COMMENT '当天待审核次数',
  `day_approval_sum`  int(11) NOT NULL DEFAULT '0' COMMENT '当天待审核金额',
  `day_approval_person_count`  int(11) NOT NULL DEFAULT '0' COMMENT '当天待审核人数',

  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1 2',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_appid_date_str` (`appid`, `date_str` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;


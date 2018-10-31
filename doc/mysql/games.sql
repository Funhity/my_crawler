
-- flappy_bird begin
DROP TABLE IF EXISTS `fb_user_friends`;
CREATE TABLE `fb_user_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL COMMENT '好友id',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_fid` (`user_id`,`f_id`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_fid` (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `fb_user`;
CREATE TABLE `fb_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0'  COMMENT '分数',
    `score_begin_date` varchar(10) NOT NULL DEFAULT '' COMMENT 'score字段生效的开始时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`id`);

DROP TABLE IF EXISTS `fb_user_friends_ss`;
CREATE TABLE `fb_user_friends_ss` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL COMMENT '好友id',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_fid` (`user_id`,`f_id`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_fid` (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `fb_user_ss`;
CREATE TABLE `fb_user_ss` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0'  COMMENT '分数',
    `score_begin_date` varchar(10) NOT NULL DEFAULT '' COMMENT 'score字段生效的开始时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`id`);

-- flappy_bird    end


-- 飞刀手pk begin
DROP TABLE IF EXISTS `fds_user_friends`;
CREATE TABLE `fds_user_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL COMMENT '好友id',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_fid` (`user_id`,`f_id`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_fid` (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `fds_user`;
CREATE TABLE `fds_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `source` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:微信小程序，2：厘米游戏',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`id`);
-- 朋友帮助
DROP TABLE IF EXISTS `fds_friend_help`;
CREATE TABLE `fds_friend_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL,
  `inviter_uid`  int(11) NOT NULL DEFAULT '0' COMMENT ' 邀请帮忙 的openid',
  `invit_data` varchar(30) NOT NULL DEFAULT '' COMMENT ' ',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- 飞刀手pk  end
-- 棍子pk begin
DROP TABLE IF EXISTS `gz_user`;
CREATE TABLE `gz_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`id`);

-- 棍子pk  end



-- 一跳冲天  x小游戏 begin
-- 用户皮肤表
DROP TABLE IF EXISTS `ytct_user_skin`;
CREATE TABLE `ytct_user_skin` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `skin_json` varchar(1000) NOT NULL DEFAULT '' COMMENT '皮肤json',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`openid`);
-- 兑换
DROP TABLE IF EXISTS `ytct_user_exchange`;
CREATE TABLE `ytct_user_exchange` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid`  varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `order_no` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `goods_id`  int(11)  NOT NULL  COMMENT ' ',
  `goods_name`   varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
    `status` int(11) NOT NULL DEFAULT '0' COMMENT '1 已兑换 0没兑换' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`openid`);
-- 球队
DROP TABLE IF EXISTS `ytct_fb_team_info`;
CREATE TABLE `ytct_fb_team_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `seq`  int(11) NOT NULL DEFAULT '0' COMMENT 'seq',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `final_8`  tinyint(4)  NOT NULL  DEFAULT '0' COMMENT ' 8强 1是 ',
  `champion`  tinyint(4)  NOT NULL  DEFAULT '0' COMMENT ' 冠军 1是 ',
  `second_up`  tinyint(4)  NOT NULL  DEFAULT '0' COMMENT ' 亚军 1是 ',
  `third_place`  tinyint(4)  NOT NULL  DEFAULT '0' COMMENT ' 季军 1是 ',
  `final_16`  int(11)  NOT NULL   DEFAULT '0' COMMENT ' 16强 1是 ',
  `create_time` int(11) NOT NULL DEFAULT '0',
    `receive_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;
-- 一跳冲天  x小游戏  end
-- 猫咪爱情大作战 begin
DROP TABLE IF EXISTS `mmaq_user_info`;
CREATE TABLE `mmaq_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `data_info` text NOT NULL COMMENT 'data',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- 分表
DROP TABLE IF EXISTS `mmaq_user_info_split`;
CREATE TABLE `mmaq_user_info_split` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `data_info` text NOT NULL COMMENT 'data',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`openid`);
-- 游戏信息数据表
DROP TABLE IF EXISTS `mmaq_game_data`;
CREATE TABLE `mmaq_game_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `data_info` text NOT NULL COMMENT 'data',
  `data_key` varchar(20) NOT NULL DEFAULT '' COMMENT 'key' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid_datakey` (`openid`,`data_key` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);
-- 猫咪爱情大作战 end

-- 最囧大脑 begin
DROP TABLE IF EXISTS `zjdn_user_friends`;
CREATE TABLE `zjdn_user_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL COMMENT '好友id',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_fid` (`user_id`,`f_id`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_fid` (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `zjdn_user`;
CREATE TABLE `zjdn_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `max_level` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);

DROP TABLE IF EXISTS `zjdn_info`;
CREATE TABLE `zjdn_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `data_info` text NOT NULL COMMENT 'data',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `zjdn_user_form_id`;
CREATE TABLE `zjdn_user_form_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 求助好友打开的关卡
DROP TABLE IF EXISTS `zjdn_help_open`;
CREATE TABLE `zjdn_help_open` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `inviter_openid` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 邀请帮忙打开的openid',
  `level` int(11) NOT NULL DEFAULT 0  COMMENT '关卡',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 流量充值记录
DROP TABLE IF EXISTS `zjdn_meal_order`;
CREATE TABLE `zjdn_meal_order` (
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
  `source` int(11) NOT NULL  DEFAULT '0' COMMENT '1：通关领奖 2：每通10关',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 流量充值记录
DROP TABLE IF EXISTS `zjdn_red_packet_transaction`;
CREATE TABLE `zjdn_red_packet_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `money` int(11) NOT NULL DEFAULT 0  COMMENT '金额 单位 分',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 未发放 2已发放',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 通关历史
DROP TABLE IF EXISTS `zjdn_level_history`;
CREATE TABLE `zjdn_level_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `level` int(11) NOT NULL DEFAULT 0  COMMENT '关卡',
  `cons_times` int(11) NOT NULL DEFAULT 0  COMMENT '连续通关次数',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1为使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`) ;
-- 最囧大脑  end



-- 小小鱼塘 begin
-- 用户表
DROP TABLE IF EXISTS `xxyt_user`;
CREATE TABLE `xxyt_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`id`);
-- 游戏信息数据表
DROP TABLE IF EXISTS `xxyt_game_data`;
CREATE TABLE `xxyt_game_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `data_info` text NOT NULL COMMENT 'data',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid` (`user_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`id`);
-- 道具流水表
DROP TABLE IF EXISTS `xxyt_item_transaction`;
CREATE TABLE `xxyt_item_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `item_type` int(11) NOT NULL DEFAULT '1' COMMENT ' 道具类型  1 金币 ',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '道具来源描述' ,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 follow_gzh：关注公众号 invit:邀请成功' ,
  `seq` varchar(16) NOT NULL DEFAULT '' COMMENT '随机字符串' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 邀请好友
DROP TABLE IF EXISTS `xxyt_invit_friend`;
CREATE TABLE `xxyt_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `inviter_uid` int(11) NOT NULL DEFAULT '0' COMMENT '邀请者的uid',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
   UNIQUE KEY `u_idx_uid_iuid` (`user_id`,`inviter_uid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 小小鱼塘 end



-- 极品飞车 begin
-- 用户表
DROP TABLE IF EXISTS `jpfc_user`;
CREATE TABLE `jpfc_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `unionid` varchar(50) NOT NULL DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`openid`);
-- 游戏信息数据表
DROP TABLE IF EXISTS `jpfc_game_data`;
CREATE TABLE `jpfc_game_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `data_info` text NOT NULL COMMENT 'data',
  `data_key` varchar(20) NOT NULL DEFAULT '' COMMENT 'key' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid` (`user_id`,`data_key` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 道具流水表
DROP TABLE IF EXISTS `jpfc_item_transaction`;
CREATE TABLE `jpfc_item_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `item_type` int(11) NOT NULL DEFAULT '1' COMMENT ' 道具类型  1 金币 ',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '道具来源描述' ,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 follow_gzh：关注公众号 invit:邀请成功' ,
  `seq` varchar(16) NOT NULL DEFAULT '' COMMENT '随机字符串' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid_seq` (`user_id`,`seq`),
  KEY `idx_uid_action_type` (`user_id`,`action_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 邀请好友
DROP TABLE IF EXISTS `jpfc_invit_friend`;
CREATE TABLE `jpfc_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `inviter_uid` int(11) NOT NULL DEFAULT '0' COMMENT '邀请者的uid',
  `scene` varchar(16) NOT NULL DEFAULT '' COMMENT '场景值' ,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_iuid_scene` (`user_id`,`inviter_uid`,`scene` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- 每日任务
DROP TABLE IF EXISTS `jpfc_task`;
CREATE TABLE `jpfc_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `item_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具或机会等',
  `item_type` varchar(20)  NOT NULL DEFAULT '0' COMMENT '道具类型 gold:金币',
  `day` int(11) NOT NULL DEFAULT '0' COMMENT '第几天',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `sub_item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1 已领取奖励',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_openid_daystr` (`user_id`,`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 极品飞车 end



-- 萌萌水族箱 begin
-- 用户表
DROP TABLE IF EXISTS `mmszx_user`;
CREATE TABLE `mmszx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `unionid` varchar(50) NOT NULL DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT 'level',
  `source` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:微信小程序，2：厘米游戏',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` ),
  KEY `idx_level` (`level`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`openid`);
-- 游戏信息数据表
DROP TABLE IF EXISTS `mmszx_game_data`;
CREATE TABLE `mmszx_game_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `data_info` text NOT NULL COMMENT 'data',
  `data_key` varchar(20) NOT NULL DEFAULT '' COMMENT 'key' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid` (`user_id`,`data_key` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 道具流水表
DROP TABLE IF EXISTS `mmszx_item_transaction`;
CREATE TABLE `mmszx_item_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `item_type` int(11) NOT NULL DEFAULT '1' COMMENT ' 道具类型  1 金币 ',
  `desc` varchar(200) NOT NULL DEFAULT '' COMMENT '道具来源描述' ,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 follow_gzh：关注公众号 invit:邀请成功' ,
  `seq` varchar(16) NOT NULL DEFAULT '' COMMENT '随机字符串' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid_seq` (`user_id`,`seq`),
  KEY `idx_uid_action_type` (`user_id`,`action_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 邀请好友
DROP TABLE IF EXISTS `mmszx_invit_friend`;
CREATE TABLE `mmszx_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `inviter_uid` int(11) NOT NULL DEFAULT '0' COMMENT '邀请者的uid',
  `scene` varchar(16) NOT NULL DEFAULT '' COMMENT '场景值' ,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_iuid_scene` (`user_id`,`inviter_uid`,`scene` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 每日任务
DROP TABLE IF EXISTS `mmszx_task`;
CREATE TABLE `mmszx_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `item_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具或机会等',
  `item_type` varchar(20)  NOT NULL DEFAULT '0' COMMENT '道具类型 gold:金币',
  `day` int(11) NOT NULL DEFAULT '0' COMMENT '第几天',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `sub_item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1 已领取奖励',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_openid_daystr` (`user_id`,`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 米大师支付 订单
DROP TABLE IF EXISTS `mmszx_midas_order`;
CREATE TABLE `mmszx_midas_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `zone_id`  varchar(20) NOT NULL DEFAULT '' COMMENT '游戏服务器大区id,',
  `pf` varchar(10) NOT NULL DEFAULT '' COMMENT '平台 安卓：android',
  `user_ip` varchar(16) NOT NULL DEFAULT 0 COMMENT '用户外网 IP',
  `amt` int(11) NOT NULL DEFAULT '0' COMMENT '扣除游戏币数量，不能为 0',
  `bill_no` varchar(64) NOT NULL DEFAULT 0 COMMENT '订单号，业务需要保证全局唯一；相同的订单号不会重复扣款。',
  `pay_item` varchar(20) NOT NULL DEFAULT 0 COMMENT '道具名称',
  `app_remark` varchar(20) NOT NULL DEFAULT 0 COMMENT '备注。会写到账户流水',
  `wx_errcode` varchar(10) NOT NULL DEFAULT 0 COMMENT '微信 错误码',
  `wx_errmsg` varchar(255) NOT NULL DEFAULT 0 COMMENT '微信 	错误信息',
  `wx_bill_no` varchar(64) NOT NULL DEFAULT 0 COMMENT '微信 订单号，有效期是 48 小时',
  `wx_balance`  int(11) NOT NULL DEFAULT '0'  COMMENT '微信 预扣后的余额',
  `wx_cancel_errcode` varchar(10) NOT NULL DEFAULT 0 COMMENT '微信 取消支付 错误码',
  `wx_cancel_errmsg` varchar(255) NOT NULL DEFAULT 0 COMMENT '微信 取消支付 错误信息',
  `wx_cancel_bill_no` varchar(64) NOT NULL DEFAULT 0 COMMENT '微信 取消支付 订单号，有效期是 48 小时',
  `wx_used_gen_amt`  int(11) NOT NULL DEFAULT '0'  COMMENT '微信 本次扣的赠送币的金额',
  `status` tinyint(0) NOT NULL DEFAULT 1 COMMENT '支付状态 1 未支付 2微信支付成功 3微信支付失败  ',
  `cancel_status` tinyint(0) NOT NULL DEFAULT 1 COMMENT '取消支付状态 1 取消成功 2取消失败 ',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
   PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id` ),
  KEY `idx_openid` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 萌萌水族箱 end

-- 水果忍者 begin
-- 用户表
DROP TABLE IF EXISTS `sgrz_user`;
CREATE TABLE `sgrz_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`openid`);
-- 游戏信息数据表
DROP TABLE IF EXISTS `sgrz_game_data`;
CREATE TABLE `sgrz_game_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `data_info` text NOT NULL COMMENT 'data',
  `data_key` varchar(20) NOT NULL DEFAULT '' COMMENT 'key' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid_data_key` (`openid`,`data_key` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`openid`);
-- 邀请好友
DROP TABLE IF EXISTS `sgrz_invit_friend`;
CREATE TABLE `sgrz_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `inviter_openid`  varchar(50) NOT NULL DEFAULT '' COMMENT '邀请者的openid',
  `scene` varchar(16) NOT NULL DEFAULT '' COMMENT '场景值' ,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid_iopenid_scene` (`openid`,`inviter_openid`,`scene` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 水果忍者 end



-- 打怪萌娘 begin
-- 用户表
DROP TABLE IF EXISTS `dgmn_user`;
CREATE TABLE `dgmn_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` )
-- 游戏信息数据表
DROP TABLE IF EXISTS `dgmn_game_data`;
CREATE TABLE `dgmn_game_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `data_info` text NOT NULL COMMENT 'data',
  `data_key` varchar(20) NOT NULL DEFAULT '' COMMENT 'key' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid` (`user_id`,`data_key` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 邀请好友
DROP TABLE IF EXISTS `dgmn_invit_friend`;
CREATE TABLE `dgmn_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `inviter_uid` int(11) NOT NULL DEFAULT '0' COMMENT '邀请者的uid',
  `scene` varchar(16) NOT NULL DEFAULT '' COMMENT '场景值' ,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_iuid_scene` (`user_id`,`inviter_uid`,`scene` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8   dbpartition by hash(`user_id`);
-- 米大师支付 订单
DROP TABLE IF EXISTS `dgmn_midas_order`;
CREATE TABLE `dgmn_midas_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `zone_id`  varchar(20) NOT NULL DEFAULT '' COMMENT '游戏服务器大区id,',
  `pf` varchar(10) NOT NULL DEFAULT '' COMMENT '平台 安卓：android',
  `user_ip` varchar(16) NOT NULL DEFAULT 0 COMMENT '用户外网 IP',
  `amt` int(11) NOT NULL DEFAULT '0' COMMENT '扣除游戏币数量，不能为 0',
  `bill_no` varchar(64) NOT NULL DEFAULT 0 COMMENT '订单号，业务需要保证全局唯一；相同的订单号不会重复扣款。',
  `pay_item` varchar(20) NOT NULL DEFAULT 0 COMMENT '道具名称',
  `app_remark` varchar(20) NOT NULL DEFAULT 0 COMMENT '备注。会写到账户流水',
  `wx_errcode` varchar(10) NOT NULL DEFAULT 0 COMMENT '微信 错误码',
  `wx_errmsg` varchar(255) NOT NULL DEFAULT 0 COMMENT '微信 	错误信息',
  `wx_bill_no` varchar(64) NOT NULL DEFAULT 0 COMMENT '微信 订单号，有效期是 48 小时',
  `wx_balance`  int(11) NOT NULL DEFAULT '0'  COMMENT '微信 预扣后的余额',
  `wx_cancel_errcode` varchar(10) NOT NULL DEFAULT 0 COMMENT '微信 取消支付 错误码',
  `wx_cancel_errmsg` varchar(255) NOT NULL DEFAULT 0 COMMENT '微信 取消支付 错误信息',
  `wx_cancel_bill_no` varchar(64) NOT NULL DEFAULT 0 COMMENT '微信 取消支付 订单号，有效期是 48 小时',
  `wx_used_gen_amt`  int(11) NOT NULL DEFAULT '0'  COMMENT '微信 本次扣的赠送币的金额',
  `status` tinyint(0) NOT NULL DEFAULT 1 COMMENT '支付状态 1 未支付 2微信支付成功 3微信支付失败  ',
  `cancel_status` tinyint(0) NOT NULL DEFAULT 1 COMMENT '取消支付状态 1 取消成功 2取消失败 ',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
   PRIMARY KEY (`id`),
  KEY `idx_userid` (`user_id` ),
  KEY `idx_openid` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 打怪萌娘 end


-- 双双游戏 公众号  begin
-- 用户表
DROP TABLE IF EXISTS `ssgzh_user`;
CREATE TABLE `ssgzh_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `unionid` varchar(50) NOT NULL DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `wx_tag_id` varchar(10) NOT NULL DEFAULT '微信标签id',
  `wx_tag_name` varchar(50) NOT NULL DEFAULT '微信标签名称',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid` (`openid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`openid`);

-- 双双游戏 公众号  end

-- 奶酪 begin
-- 邀请好友
DROP TABLE IF EXISTS `nailao_invit_friend`;
CREATE TABLE `nailao_invit_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `inviter_openid`  varchar(50) NOT NULL DEFAULT '' COMMENT '邀请者的openid',
  `scene` varchar(16) NOT NULL DEFAULT '' COMMENT '场景值' ,
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_openid_iopenid_scene` (`openid`,`inviter_openid`,`scene` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
-- 奶酪 end

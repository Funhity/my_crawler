
-- 用户表 总表  包含手机登录、微信登录（app\小程序\小游戏） 的信息及绑定的手机信息
DROP TABLE IF EXISTS `ssg_user`;
CREATE TABLE `ssg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '微信小游戏 openid',
  `unionid` varchar(50) NOT NULL DEFAULT '' COMMENT 'unionid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `total_combat` int(11) NOT NULL DEFAULT '0' COMMENT '对战总场数',
  `total_combat_win` int(11) NOT NULL DEFAULT '0' COMMENT '对战赢得总场数',
  `total_combat_lost` int(11) NOT NULL DEFAULT '0' COMMENT '对战输得总场数',
  `is_robot`  tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否机器人 1是  0不是',
  `is_import_to_txy` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已导入到腾讯云 云通讯 1是  0不是',
  `phone` varchar(15) NOT NULL DEFAULT '绑定的手机号码',
  `login_name` varchar(15) NOT NULL DEFAULT '用户登录手机号等，非第三方登录',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`id`);

-- 用户表 app微信登录的信息
DROP TABLE IF EXISTS `ssg_user_wx_app`;
CREATE TABLE `ssg_user_wx_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '微信 openid',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 双双游戏和其他游戏的用户间的关系
DROP TABLE IF EXISTS `ssg_user_game_uid`;
CREATE TABLE `ssg_user_game_uid` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `game_user_id` int(11) NOT NULL  DEFAULT 0 COMMENT '游戏的uid',
  `game_name`  varchar(20) NOT NULL  COMMENT '游戏名称 字母',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_guid_gname` (`user_id`,`game_user_id`,`game_name`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_fid` (`user_id`, `game_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);
-- 用户金额表
DROP TABLE IF EXISTS `ssg_user_money`;
CREATE TABLE `ssg_user_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `total_money` int(11) NOT NULL  DEFAULT 0 COMMENT '总金额 单位分',
  `remain_money` int(11) NOT NULL  DEFAULT 0 COMMENT '总金额 单位分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid` (`user_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 用户道具表
DROP TABLE IF EXISTS `ssg_user_item`;
CREATE TABLE `ssg_user_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL  DEFAULT 0 COMMENT '用户id',
  `date_str` varchar(8) NOT NULL  DEFAULT 0 COMMENT '日期 如 20180101',
  `item_type` int(11) NOT NULL  DEFAULT 0 COMMENT '道具类型  1金币',
  `total_item` int(11) NOT NULL  DEFAULT 0 COMMENT '总道具',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_item_type_date_str` (`user_id`,`date_str`,`item_type` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 金额流水表
DROP TABLE IF EXISTS `ssg_user_money_transaction`;
CREATE TABLE `ssg_user_money_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `value` int(11) NOT NULL  DEFAULT 0 COMMENT '获得或支付的金额 单位分',
  `remain_money` int(11) NOT NULL DEFAULT 0 COMMENT '剩余金额 单位分',
  `source` int(11) NOT NULL  DEFAULT 0 COMMENT '来源 ',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 用户道具流水表
DROP TABLE IF EXISTS `ssg_user_item_transaction`;
CREATE TABLE `ssg_user_item_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL  DEFAULT 0 COMMENT '用户id',
  `item_type` int(11) NOT NULL  DEFAULT 0 COMMENT '道具类型  1金币',
  `value` int(11) NOT NULL  DEFAULT 0 COMMENT '使用或获得的道具 单位分',
  `total_item` int(11) NOT NULL DEFAULT 0 COMMENT '剩余道具',
  `item_source` int(11) NOT NULL DEFAULT 0 COMMENT '道具来源',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);

-- 每日任务
DROP TABLE IF EXISTS `ssg_day_task`;
CREATE TABLE `ssg_day_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `prop_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具或机会等',
  `day` int(11) NOT NULL DEFAULT '0' COMMENT '第几天',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `sub_item_key` varchar(20) NOT NULL DEFAULT 0 COMMENT 'item key',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1 已领取奖励',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_user_id_day` (`user_id`,`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);

-- 邀请好友
DROP TABLE IF EXISTS `ssg_invite_friend`;
CREATE TABLE `ssg_invite_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `o_uid` int(11) NOT NULL DEFAULT '0' COMMENT '朋友的用户id',
  `prop_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具或机会等',
  `item_type` int(11) NOT NULL  DEFAULT 0 COMMENT '道具类型  1金币',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_user_ouid` (`user_id`,`o_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 好友进贡
DROP TABLE IF EXISTS `ssg_friend_tributary`;
CREATE TABLE `ssg_friend_tributary` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `o_uid` int(11) NOT NULL DEFAULT '0' COMMENT '朋友的用户id',
  `prop_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具或机会等',
  `item_type` int(11) NOT NULL  DEFAULT 0 COMMENT '道具类型  1金币',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_user_ouid` (`user_id`,`o_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);

-- 统计流水表
DROP TABLE IF EXISTS `ssg_day_task`;
CREATE TABLE `ssg_day_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `key_md5` varchar(32) NOT NULL DEFAULT '0' COMMENT 'key_name的md5值',
  `key_name` varchar(500) NOT NULL DEFAULT '0' COMMENT 'key 名称',
  `name` varchar(500) NOT NULL DEFAULT '0' COMMENT '统计事件中文名称',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '第几天',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_user_id_day` (`user_id`,`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `ssg_app_list`;
CREATE TABLE `ssg_app_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT ,
  `appid` varchar(36) NOT NULL DEFAULT '' COMMENT 'openid',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '展示标题',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '小游戏或小程序路径 ',
  `label` varchar(50) NOT NULL DEFAULT '' COMMENT '游戏的唯一标识 ',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序 ' ,
  `position` int(11) NOT NULL DEFAULT '0' COMMENT '1、banner 2、人气正在飙升】 3、最新发布 4、大家都在玩 ' ,
  `min_person_number` int(11) NOT NULL DEFAULT '0' COMMENT '最小玩家人数 ' ,
  `max_person_number` int(11) NOT NULL DEFAULT '0' COMMENT '最大玩家人数' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT  '1有效 2 失效',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;


-- 20180802  双双游戏v2.3需求 --
CREATE TABLE `ssg_games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL COMMENT '游戏id',
  `desc` varchar(120) NOT NULL DEFAULT '' COMMENT '描述文案',
  `person_number` varchar(60) NOT NULL DEFAULT '' COMMENT '波动人数',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否最新发布游戏',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否人气飙升游戏',
  `is_all` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否大家都在玩游戏',
  `is_fight` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否对战游戏',
  `new_weight` int(4) NOT NULL DEFAULT '0' COMMENT '最新排序',
  `hot_weight` int(4) NOT NULL DEFAULT '0' COMMENT '人气排序',
  `all_weight` int(4) NOT NULL DEFAULT '0' COMMENT '都在玩排序',
  `fight_weight` int(4) NOT NULL DEFAULT '0' COMMENT '对战排序',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态，0隐藏，1显示，2删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `ssg_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL COMMENT '游戏id',
  `img` varchar(120) NOT NULL DEFAULT '' COMMENT 'banner图片',
  `weight` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态，0隐藏，1显示，2删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `ssg_hero_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '名称',
  `img` varchar(120) NOT NULL DEFAULT '' COMMENT '大会图片（小）',
  `appid` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序appid',
  `person_number` varchar(50) NOT NULL DEFAULT '' COMMENT '波动人数',
  `desc` varchar(120) NOT NULL DEFAULT '' COMMENT '描述文案',
  `weight` tinyint(4) NOT NULL COMMENT '排序',
  `path` varchar(120) NOT NULL COMMENT '小程序跳转路径',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态，0隐藏，1显示，2删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `ssg_user` ADD `total_score` int(11) NOT NULL DEFAULT '0' COMMENT '总金币' AFTER `login_name`;

CREATE TABLE `ssg_user_reward_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `reward` varchar(30) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `is_receive` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否领取，0未领取，1领取',
  `type` varchar(20) NOT NULL COMMENT '类型，lottery抽奖，task任务',
  `created_day` char(10) NOT NULL DEFAULT '' COMMENT '获取日期',
  `created` int(11) NOT NULL DEFAULT '0' COMMENT '获取时间',
  `is_virtual` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为虚拟物品',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

CREATE TABLE `ssg_user_score_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `score` smallint(6) NOT NULL DEFAULT '0',
  `event` varchar(32) NOT NULL,
  `event_info` varchar(100) DEFAULT NULL,
  `event_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `ssg_user` ADD `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录（签到）时间' AFTER `total_score`;
ALTER TABLE `ssg_user` ADD `continu_login_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '连续登录（签到）次数' AFTER `total_score`;

CREATE TABLE `ssg_user_login_log`
(
  `id` Int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `continu_login_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '连续登录（签到）次数',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `ssg_game_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `img` varchar(120) NOT NULL DEFAULT '' COMMENT '游戏图片icon',
  `pk_img` varchar(120) NOT NULL DEFAULT '' COMMENT '游戏图片icon',
  `label` varchar(60) NOT NULL DEFAULT '' COMMENT '游戏标识，拼音命名',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


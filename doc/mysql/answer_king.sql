DROP TABLE IF EXISTS `t_user_challenges`;
CREATE TABLE `t_user_challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '来源类型,1:挑战 2：对战',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `game_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1、答题 2、24点3、1-500 ',
  `result` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT ' 0 fail 1 success',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT ' 0 game over 1 processing',
   `other_user` int(11) NOT NULL DEFAULT 0  ,
  PRIMARY KEY (`id`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);



--用户中奖名单
DROP TABLE IF EXISTS `t_user_reward_name_list`;
CREATE TABLE `t_user_reward_name_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
   `nickname`  varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 分享获得机会 2获得实物奖品  ',
    `game_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1  2 3 ',
   `prize_name` varchar(50) NOT NULL COMMENT '奖品名称',
  `prize_number`  int(11) NOT NULL DEFAULT '0' COMMENT '奖品数量',
    `is_right_now` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 是否立马兑换 1是  0不是  ',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `t_user_friends`;
CREATE TABLE `t_user_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL COMMENT '好友id',
  `challenge_success` int(11) NOT NULL DEFAULT '0' COMMENT '挑战成功总数',
  `challenge_fail` int(11) NOT NULL DEFAULT '0' COMMENT '挑战失败总数',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `game_type` tinyint(1) unsigned NOT NULL DEFAULT '0' ,
  `prize_number` int(11) NOT NULL DEFAULT '0' COMMENT '奖品数量',
    `challenge_total` tinyint(1) unsigned NOT NULL DEFAULT '0'  COMMENT '挑战总数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_uid_fid` (`user_id`,`f_id`),
  KEY `auto_shard_key_user_id` (`user_id`),
  KEY `idx_fid` (`f_id`),
  KEY `idx_success` (`challenge_success`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);

DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
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
  `remain_challenge_times` int(11) NOT NULL DEFAULT '0'  COMMENT '剩余挑战次数',
  `challenge_success` int(11) NOT NULL DEFAULT COMMENT '挑战成功',
  `challenge_total` int(11) NOT NULL DEFAULT '0' COMMENT '总挑战次数',
  `score_4` int(11) NOT NULL DEFAULT '0' COMMENT 'game_type=4的分数',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`id`);



DROP TABLE IF EXISTS `t_user_reward`;
CREATE TABLE `t_user_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `order_no` varchar(32) NOT NULL COMMENT '订单号',
  `user_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `goods_cost` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成本',
  `goods_stock_no` varchar(255) NOT NULL DEFAULT '',
  `receiver_name` varchar(100) NOT NULL DEFAULT '' COMMENT '收货人',
  `receiver_mobile` char(11) NOT NULL DEFAULT '' COMMENT '收货人手机',
  `receiver_address` varchar(200) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `receiver_memo` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人备注',
  `delivery_company` varchar(100) NOT NULL DEFAULT '' COMMENT '物流公司',
  `delivery_no` varchar(100) NOT NULL DEFAULT '' COMMENT '物流单号',
  `status` smallint(11) NOT NULL DEFAULT '0' COMMENT '0：未领取，1：已领取，2：已发货,3:备货中',
  `service_memo` varchar(255) NOT NULL DEFAULT '' COMMENT '客服备注',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `challenge_id` int(11) NOT NULL DEFAULT '0',
  `exchange_time`  NOT NULL  DEFAULT '0' COMMENT '兑换时间，即goods_id有值得时间',
  PRIMARY KEY (`id`),
  KEY `idx_order_no` (`order_no`),
  KEY `auto_shard_key_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);


-- 每日任务

DROP TABLE IF EXISTS `t_day_task`;
CREATE TABLE `t_day_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `prop_value` int(11) NOT NULL DEFAULT '0' COMMENT '获得的道具',
  `game_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1  2 3 ',
  `chance` int(11) NOT NULL DEFAULT '0' COMMENT '获得的机会',
  `day` int(11) NOT NULL DEFAULT '0' COMMENT '第几天',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `item_key` varchar(10) NOT NULL DEFAULT 0 COMMENT 'item key',
  `sub_item_key` varchar(10) NOT NULL DEFAULT 0 COMMENT 'item key',
   `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '1 已领取奖励',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
   PRIMARY KEY (`id`),
  KEY `idx_user_id_day` (`user_id`,`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);


DROP TABLE IF EXISTS `t_user_score`;
CREATE TABLE `t_user_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT 'level',
   `game_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1  2 3 4 ',
   `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
   `score` int(11) NOT NULL DEFAULT '0' COMMENT ' 分数',
   `status` int(11) NOT NULL DEFAULT '1',
   `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);


DROP TABLE IF EXISTS `t_active_setting`;
CREATE TABLE `t_active_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `type` varchar(20) NOT NULL DEFAULT '0' COMMENT '类型',
  `item_total` int(11) NOT NULL DEFAULT '0' COMMENT '道具数量',
  `day_item_total` int(11) NOT NULL DEFAULT '0' COMMENT '道具数量 每天',
  `remain_day_item_total` int(11) NOT NULL DEFAULT '0' COMMENT '剩余道具数量 每天',
  `remain_item_total`  int(11) NOT NULL DEFAULT '0' COMMENT '剩余道具数量',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;


DROP TABLE IF EXISTS `t_action_statistics`;
CREATE TABLE `t_action_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `name` varchar(50) NOT NULL DEFAULT '0' COMMENT ' ',
  `t_key` varchar(20) NOT NULL DEFAULT '0' COMMENT ' ',
  `show_total` int(11) NOT NULL DEFAULT '0' COMMENT '展现量',
  `person_total` int(11) NOT NULL DEFAULT '0' COMMENT '总人数',
  `person_click_total` int(11) NOT NULL DEFAULT '0' COMMENT '点击人数',
  `click_total` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `play_long` int(11) NOT NULL DEFAULT '0' COMMENT '播放时长',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '统计日期 日期字符串 20180101',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;



DROP TABLE IF EXISTS `t_user_form_id`;
CREATE TABLE `t_user_form_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT  ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

DROP TABLE IF EXISTS `t_question_jiajian`;
CREATE TABLE `t_question_jiajian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:easy,2:hard',
  `is_right` tinyint(1) NOT NULL  DEFAULT '0' COMMENT '是否正确 1正确 2不正确' ,
  `answer` smallint(4) NOT NULL  DEFAULT '0' COMMENT '答案 ' ,
  `number1` smallint(4) NOT NULL DEFAULT '0' COMMENT '数字1',
  `number2` smallint(4) NOT NULL DEFAULT '0' COMMENT '数字2',
  `operator` char(1) NOT NULL DEFAULT '' COMMENT '运算符',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1212 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `t_user_answers_jiajian`;
CREATE TABLE `t_user_answers_jiajian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT 'id',
  `challenge_id` int(11) NOT NULL COMMENT 'challenge_id',
  `result` smallint(4) NOT NULL DEFAULT '0' COMMENT '1:0:',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_id_qid` (`user_id`,`question_id`,`challenge_id`),
  KEY `auto_shard_key_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=602211 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `t_user_lottery_history`;
CREATE TABLE `t_user_lottery_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '抽奖标题',
  `desc` varchar(50) NOT NULL COMMENT '抽奖描述',
  `l_status` varchar(10) NOT NULL DEFAULT '0' COMMENT '抽奖状态',
  `type` varchar(20) NOT NULL DEFAULT '0' COMMENT '抽奖类型',
  `item_number` int(11) NOT NULL DEFAULT '0' COMMENT '道具数量',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `auto_shard_key_user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=602211 DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);



--  答题类 加减、拼音、汉字 猜颜色、猜明星 begin
-- 用户表 加减
DROP TABLE IF EXISTS `dt_user_jia_jian`;
CREATE TABLE `dt_user_jia_jian` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `balance` int(11) NOT NULL DEFAULT 0  COMMENT '余额',
  `total_money` int(11) NOT NULL DEFAULT 0  COMMENT '单位 分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`id`);
-- 用户表 拼音
DROP TABLE IF EXISTS `dt_user_pin_yin`;
CREATE TABLE `dt_user_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `balance` int(11) NOT NULL DEFAULT 0  COMMENT '余额',
  `total_money` int(11) NOT NULL DEFAULT 0  COMMENT '单位 分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`id`);
-- 用户表 汉字
DROP TABLE IF EXISTS `dt_user_han_zi`;
CREATE TABLE `dt_user_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `balance` int(11) NOT NULL DEFAULT 0  COMMENT '余额',
  `total_money` int(11) NOT NULL DEFAULT 0  COMMENT '单位 分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`id`);
-- 用户表 猜颜色
DROP TABLE IF EXISTS `dt_user_color`;
CREATE TABLE `dt_user_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `balance` int(11) NOT NULL DEFAULT 0  COMMENT '余额',
  `total_money` int(11) NOT NULL DEFAULT 0  COMMENT '单位 分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`id`);
-- 用户表 猜明星
DROP TABLE IF EXISTS `dt_user_star`;
CREATE TABLE `dt_user_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=女, 1=男',
  `city` varchar(30) NOT NULL DEFAULT '',
  `province` varchar(30) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `avatar` varchar(200) NOT NULL DEFAULT '',
  `gold_coin` int(11) NOT NULL DEFAULT 0  COMMENT '金币',
  `balance` int(11) NOT NULL DEFAULT 0  COMMENT '余额',
  `total_money` int(11) NOT NULL DEFAULT 0  COMMENT '单位 分',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  dbpartition by hash(`id`);

-- 挑战表
DROP TABLE IF EXISTS `dt_challenge_jia_jian`;
CREATE TABLE `dt_challenge_jia_jian` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未获得结果 1 挑战成功 2失败',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_challenge_han_zi`;
CREATE TABLE `dt_challenge_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未获得结果 1 挑战成功 2失败',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_challenge_pin_yin`;
CREATE TABLE `dt_challenge_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未获得结果 1 挑战成功 2失败',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_challenge_color`;
CREATE TABLE `dt_challenge_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未获得结果 1 挑战成功 2失败',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_challenge_star`;
CREATE TABLE `dt_challenge_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT BY GROUP,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未获得结果 1 挑战成功 2失败',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 金额流水表
DROP TABLE IF EXISTS `dt_blc_trans_jia_jian`;
CREATE TABLE `dt_blc_trans_jia_jian` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `ch_id` int(11) NOT NULL DEFAULT '0' COMMENT 'dt_challenge_* 挑战记录id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 c_succ：挑战成功' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_blc_trans_pin_yin`;
CREATE TABLE `dt_blc_trans_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `ch_id` int(11) NOT NULL DEFAULT '0' COMMENT 'dt_challenge_* 挑战记录id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 c_succ：挑战成功' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_blc_trans_han_zi`;
CREATE TABLE `dt_blc_trans_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `ch_id` int(11) NOT NULL DEFAULT '0' COMMENT 'dt_challenge_* 挑战记录id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 c_succ：挑战成功' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_blc_trans_color`;
CREATE TABLE `dt_blc_trans_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `ch_id` int(11) NOT NULL DEFAULT '0' COMMENT 'dt_challenge_* 挑战记录id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 c_succ：挑战成功' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
DROP TABLE IF EXISTS `dt_blc_trans_star`;
CREATE TABLE `dt_blc_trans_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `ch_id` int(11) NOT NULL DEFAULT '0' COMMENT 'dt_challenge_* 挑战记录id',
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '获得的金币 ',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '',
  `action_type` varchar(16) NOT NULL DEFAULT '' COMMENT '操作类型 c_succ：挑战成功' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 提现订单表
DROP TABLE IF EXISTS `dt_withdraw`;
CREATE TABLE `dt_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT   ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT 'user_id',
  `order_no` varchar(16) NOT NULL DEFAULT '' COMMENT '订单号' ,
  `value` int(11) NOT NULL DEFAULT 0  COMMENT '提现金额 ',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1提现中 2已发放',
  `type` varchar(16) NOT NULL DEFAULT '' COMMENT '提现来源小程序 jia_jian:加减、pin_yin:拼音、han_zi:汉字 color:猜颜色、star:猜明星' ,
  `source` varchar(16) NOT NULL DEFAULT '' COMMENT '提现类别 1 提现' ,
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期 20180101' ,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;
-- 汉子 题库
DROP TABLE IF EXISTS `dt_question_han_zi`;
CREATE TABLE `dt_question_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:easy,2:hard',
  `r_answer` varchar(3) NOT NULL  DEFAULT '0' COMMENT '正确答案 ' ,
  `w_answer` varchar(3) NOT NULL  DEFAULT '0' COMMENT '错误答案 ' ,
  `title` varchar(20) NOT NULL  DEFAULT '0' COMMENT '题目 ' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 汉子 已答题目
DROP TABLE IF EXISTS `dt_answers_han_zi`;
CREATE TABLE `dt_answers_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT 'id',
  `challenge_id` int(11) NOT NULL COMMENT 'challenge_id',
  `result` smallint(4) NOT NULL DEFAULT '0' COMMENT '1答对2答错',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `auto_shard_key_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 拼音 题库
DROP TABLE IF EXISTS `dt_question_pin_yin`;
CREATE TABLE `dt_question_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:easy,2:hard',
  `word1` varchar(3) NOT NULL  DEFAULT '0' COMMENT '字1 ' ,
  `word2` varchar(3) NOT NULL  DEFAULT '0' COMMENT '字2 ' ,
  `w_idx`  tinyint(1) NOT NULL  DEFAULT '0' COMMENT '拼音字 1：字1 2：字2 ' ,
  `pinyin1` varchar(10) NOT NULL  DEFAULT '0' COMMENT '拼音1 ' ,
  `pinyin2` varchar(10) NOT NULL  DEFAULT '0' COMMENT '拼音2 ' ,
  `p_idx` tinyint(1) NOT NULL  DEFAULT '0' COMMENT '正确的拼音 1：拼音1 2：拼音2  ' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 拼音 题库
DROP TABLE IF EXISTS `dt_question_color`;
CREATE TABLE `dt_question_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:easy,2:hard',
  `word` varchar(3) NOT NULL  DEFAULT '0' COMMENT '字' ,
  `r_colors` varchar(500) NOT NULL  DEFAULT '0' COMMENT '正确的颜色json 颜色值为 #000000 ' ,
  `w_colors` varchar(500) NOT NULL  DEFAULT '0' COMMENT '错误的颜色json ' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 拼音  已答题目
DROP TABLE IF EXISTS `dt_answers_pin_yin`;
CREATE TABLE `dt_answers_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT 'id',
  `challenge_id` int(11) NOT NULL COMMENT 'challenge_id',
  `result` smallint(4) NOT NULL DEFAULT '0' COMMENT '1答对2答错',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `auto_shard_key_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 看图猜明星 题库
DROP TABLE IF EXISTS `dt_question_star`;
CREATE TABLE `dt_question_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:easy,2:hard',
  `opt1` varchar(20) NOT NULL  DEFAULT '0' COMMENT '选项1 ' ,
  `opt2` varchar(20) NOT NULL  DEFAULT '0' COMMENT '选项2 ' ,
  `opt3` varchar(20) NOT NULL  DEFAULT '0' COMMENT '选项3 ' ,
  `opt4` varchar(20) NOT NULL  DEFAULT '0' COMMENT '选项4 ' ,
  `opt_idx`  tinyint(1) NOT NULL  DEFAULT '0' COMMENT '对的选项 1：选项1 2：选项2  3：选项3 4：选项4  ' ,
  `src` varchar(500) NOT NULL  DEFAULT '0' COMMENT '明星图片 ' ,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- 看图猜明星  已答题目
DROP TABLE IF EXISTS `dt_answers_star`;
CREATE TABLE `dt_answers_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT   BY GROUP,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT 'id',
  `challenge_id` int(11) NOT NULL COMMENT 'challenge_id',
  `result` smallint(4) NOT NULL DEFAULT '0' COMMENT '1答对2答错',
  `create_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `auto_shard_key_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 dbpartition by hash(`user_id`);
-- 任务表 加减
DROP TABLE IF EXISTS `dt_task_jia_jian`;
CREATE TABLE `dt_task_jia_jian` (
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
-- 任务表 color
DROP TABLE IF EXISTS `dt_task_color`;
CREATE TABLE `dt_task_color` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);-- 任务表 color
DROP TABLE IF EXISTS `dt_task_star`;
CREATE TABLE `dt_task_star` (
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
-- form id 加减
DROP TABLE IF EXISTS `dt_form_id_jia_jian`;
CREATE TABLE `dt_form_id_jia_jian` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_formid` (`user_id`,`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- form id 颜色
DROP TABLE IF EXISTS `dt_form_id_color`;
CREATE TABLE `dt_form_id_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_formid` (`user_id`,`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- form id 汉字
DROP TABLE IF EXISTS `dt_form_id_han_zi`;
CREATE TABLE `dt_form_id_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_formid` (`user_id`,`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- form id 拼音
DROP TABLE IF EXISTS `dt_form_id_pin_yin`;
CREATE TABLE `dt_form_id_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_formid` (`user_id`,`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- form id 明星
DROP TABLE IF EXISTS `dt_form_id_star`;
CREATE TABLE `dt_form_id_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `form_id` varchar(50) NOT NULL DEFAULT '0' COMMENT ' 微信小程序form id',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未使用 2已使用',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_idx_uid_formid` (`user_id`,`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
--  答题类 加减、拼音、汉字 猜颜色、猜明星 end
-- 待推送列表 加减
DROP TABLE IF EXISTS `dt_wait_push_msg_jia_jian`;
CREATE TABLE `dt_wait_push_msg_jia_jian` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 click_share:点击分享的推送 start_challenge:开始挑战',
  `push_res_json` text not null COMMENT '微信返回的发送结果json',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未推送 2推送成功 3推送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` (`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 待推送列表 颜色
DROP TABLE IF EXISTS `dt_wait_push_msg_color`;
CREATE TABLE `dt_wait_push_msg_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 click_share:点击分享的推送 start_challenge:开始挑战',
  `push_res_json` text not null COMMENT '微信返回的发送结果json',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未推送 2推送成功 3推送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` (`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 待推送列表 汉子
DROP TABLE IF EXISTS `dt_wait_push_msg_han_zi`;
CREATE TABLE `dt_wait_push_msg_han_zi` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 click_share:点击分享的推送 start_challenge:开始挑战',
  `push_res_json` text not null COMMENT '微信返回的发送结果json',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未推送 2推送成功 3推送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` (`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 待推送列表 猜明星
DROP TABLE IF EXISTS `dt_wait_push_msg_star`;
CREATE TABLE `dt_wait_push_msg_star` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 click_share:点击分享的推送 start_challenge:开始挑战',
  `push_res_json` text not null COMMENT '微信返回的发送结果json',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未推送 2推送成功 3推送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` (`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 待推送列表 拼音
DROP TABLE IF EXISTS `dt_wait_push_msg_pin_yin`;
CREATE TABLE `dt_wait_push_msg_pin_yin` (
  `id` int(11) NOT NULL AUTO_INCREMENT  BY GROUP ,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 first_share:点击分享的推送 start_challenge:开始挑战',
  `push_res_json` text not null COMMENT '微信返回的发送结果json',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1未推送 2推送成功 3推送失败',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_str` (`date_str`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  dbpartition by hash(`user_id`);
-- 微信模板消息推送统计表
DROP TABLE IF EXISTS `dt_push_wx_msg_stati`;
CREATE TABLE `dt_push_wx_msg_stati` (
  `id` int(11) NOT NULL AUTO_INCREMENT   ,
  `appid` varchar(36) NOT NULL DEFAULT '' COMMENT 'appid',
  `app_no` varchar(20) NOT NULL DEFAULT '' COMMENT 'app_no',
  `app_name` varchar(20) NOT NULL DEFAULT '' COMMENT 'app name',
  `date_str` varchar(8) NOT NULL DEFAULT '' COMMENT '日期字符串 20180101',
  `push_type` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送类型 first_share:首次点击分享的推送 first_challenge:首次挑战',
  `push_type_cn` varchar(20) NOT NULL DEFAULT '0' COMMENT '推送名称 first_share:首次点击分享的推送 first_challenge:首次挑战',
  `no_push`  int(11) NOT NULL DEFAULT '0' COMMENT '未推送',
  `push_succ`  int(11) NOT NULL DEFAULT '0' COMMENT '推送成功',
  `push_fail`  int(11) NOT NULL DEFAULT '0'  COMMENT '推送失败',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT ' 1 2',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_appid_datestr_pushtype` (`appid`,`date_str`,`push_type`),
  KEY `idx_appno` (`app_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8  ;
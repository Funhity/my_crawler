 CREATE DATABASE `starrank_db`;
 
 CREATE TABLE `t_users` (
  `uid` int(10) NOT NULL auto_increment,
  `openid` varchar(64) NOT NULL,
  `nickname` varchar(255) NOT NULL,   
  `avatar` varchar(255) NOT NULL,
  `score` int(10) NOT NULL DEFAULT 0 COMMENT '剩余火币',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:禁用,1:正常', 
  `from_uid` int(10) NOT NULL COMMENT '邀请用户id',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`uid`)
)ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COMMENT '用户表';

 CREATE TABLE `t_users_score_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `star_id` int(10) NOT NULL COMMENT '明星id',
  `use_type` tinyint(1) NOT NULL COMMENT '1:收入,2:消耗',
  `reason` tinyint(1) NOT NULL COMMENT '1:注册,2:签到,3:分享,4:打榜',
  `score` int(10) NOT NULL COMMENT '火币',
  `hot` int(10) NOT NULL COMMENT '热度',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '火币流水表';

 CREATE TABLE `t_users_invite_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `from_uid` int(10) NOT NULL default 0 COMMENT '冗余邀请用户id',  
  `share_type` tinyint(1) NOT NULL COMMENT '0:其它,1:平常分享,2:有奖分享',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '分享邀请流水表';

 CREATE TABLE `t_users_share_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `star_id` int(10) NOT NULL,
  `share_type` tinyint(1) NOT NULL COMMENT '0:其它,1:平常分享,2:有奖分享',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '分享流水表';

CREATE TABLE `t_stars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '明星姓名',
  `avatar` varchar(300) NOT NULL DEFAULT '' COMMENT '明星头像图',
  `cover` varchar(500) NOT NULL DEFAULT '' COMMENT '明星封面大图',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '状态：1上架，2下架，0删除',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '自动时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8mb4 COMMENT='明星表';

CREATE TABLE `t_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(300) NOT NULL DEFAULT '' COMMENT '素材图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `pos` tinyint(4) NOT NULL DEFAULT '1' COMMENT '素材显示位置：1:Banner',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `jump_type` smallint(4) NOT NULL DEFAULT '1' COMMENT '跳转类型：0无跳转、1外部小程序、2URL链接',
  `appid` varchar(32) NOT NULL DEFAULT '' COMMENT '外部小程序APPID',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT 'path',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT 'URL链接',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '状态：1上架，2下架，0删除',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '自动时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8mb4 COMMENT='通用素材配置表(Banner)';


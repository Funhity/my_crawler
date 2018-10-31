CREATE TABLE `t_guess_song_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(300) NOT NULL DEFAULT '' COMMENT '素材图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `pos` tinyint(4) NOT NULL DEFAULT '1' COMMENT '素材显示位置：1首页、2通知中心',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `jump_type` smallint(4) NOT NULL DEFAULT '1' COMMENT '跳转类型：0无跳转、1外部小程序、2URL链接、3入闯关模式、4排位赛主页、5蒙面歌神主页、6歌单闯关主页、7任务中心主页',
  `appid` varchar(32) NOT NULL DEFAULT '' COMMENT '外部小程序APPID',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT 'path',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT 'URL链接',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '状态：1上架，2下架，0删除',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '自动时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COMMENT='猜歌通用素材配置表(首页弹窗、通知中心)';

CREATE TABLE `t_guess_song_gift_center` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `image` varchar(300) NOT NULL DEFAULT '' COMMENT '素材图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '福利类型：1限时领音符、2专属福利',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '奖励音符',
  `sort` smallint(4) NOT NULL DEFAULT '0' COMMENT '位置序号', 
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `jump_type` smallint(4) NOT NULL DEFAULT '1' COMMENT '跳转类型：0无跳转、1外部小程序、2URL链接、3入闯关模式、4排位赛主页、5蒙面歌神主页、6歌单闯关主页、7任务中心主页',
  `appid` varchar(32) NOT NULL DEFAULT '' COMMENT '外部小程序APPID',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT 'path',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT 'URL链接',
  `status` tinyint(4) NOT NULL DEFAULT '2' COMMENT '状态：1上架，2下架，0删除',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '自动时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COMMENT='福利中心配置表';
 
CREATE TABLE `t_guess_song_material_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `material_id` int(10) NOT NULL COMMENT 't_guess_song_material.id',
  `pos` tinyint(4) NOT NULL DEFAULT '1' COMMENT '素材显示位置：1首页、2通知中心',
  `type` tinyint(4) NOT NULL COMMENT '类型,1:展示,2:点击',   
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '日志' dbpartition by hash(`uid`) tbpartition by hash(`uid`) tbpartitions 8;

CREATE TABLE `t_guess_song_gift_center_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `gift_id` int(10) NOT NULL COMMENT 't_guess_song_gift_center.id',
  `type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '类型:1:领取,2:点击',   
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '领取音符',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx1` (`gift_id`,`type`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '福利中心日志' dbpartition by hash(`uid`) tbpartition by hash(`uid`) tbpartitions 8;



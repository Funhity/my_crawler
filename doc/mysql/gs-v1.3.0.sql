
CREATE TABLE `t_guess_song_user_mask_ticket` (
  `uid` int(10) NOT NULL,
  `amount` int(10) NOT NULL DEFAULT 0 comment '入场券总数量',
  `score` int(10) NOT NULL DEFAULT 0 comment '积分总数',
  `create_time` int(10) NOT NULL DEFAULT 0 comment '创建时间',
  PRIMARY KEY (`uid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '蒙面模式入场券' dbpartition by hash(`uid`) tbpartition by hash(`uid`) tbpartitions 8;

CREATE TABLE `t_guess_song_user_mask_ticket_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `type` int(10) NOT NULL comment '类型,1:每日登陆赠送,2:登陆后每小时赠送,3:邀请新用户赠送,4:消耗',  
  `amount` int(10) NOT NULL DEFAULT 0 comment '入场券数量',
  `pass` int(10) NOT NULL DEFAULT 0 comment '答对题目数',
  `result` int(10) NOT NULL DEFAULT 0 comment '消耗结果,1:成功,0:失败',    
  `create_time` int(10) NOT NULL DEFAULT 0 comment '创建时间',
  PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '蒙面模式入场券日志' dbpartition by hash(`uid`) tbpartition by hash(`uid`) tbpartitions 8;

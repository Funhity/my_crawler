CREATE TABLE `t_guess_song_user_p2p_log` (
  `id` int(10) NOT NULL auto_increment,
  `uid` int(10) NOT NULL,
  `room_id` int(10) NOT NULL comment '房号',
  `result` int(10) NOT NULL comment '0:平局,1:挑战成功,2:挑战失败',
  `create_time` int(10) NOT NULL DEFAULT 0 comment '创建时间',
  PRIMARY KEY (`id`),
  key `group_key` (`create_time`,`uid`,`result`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 comment '好友对战日志表' dbpartition by hash(`uid`) tbpartition by hash(`uid`) tbpartitions 8;

--用于对战新用户来源统计
alter table `t_guess_song_user_info` add column `source` varchar(10) DEFAULT '' comment '来源,p2p:好友对战';
alter table `t_guess_song_user_info` add key idx_source_time(`source`,`reg_time`);
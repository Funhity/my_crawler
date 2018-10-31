<?php

namespace Mylib\Weixin;
use Mylib\Tools\CurlTool;

  /**
  * 图文消息处理类
  * 1. createTuwenMsg: 往数据库中插入图文消息;
  * 2. getTuwenById: 根据图文ID返回图文数据;
  * 3. getYDCTuWen: 返回"鲜菜园"图文消息;
  * 4. searchTuWenByTitle: 根据关键字查询是否有对应title的图文消息;
  * 5. searchTuWenById($tw_id): 根据图文ID查询是否有对应tw_id的图文消息；
  **/
  class WeixinTuwen{
	  
	// 1. 插入图文消息数据：
	public static function createTuwenMsg($type, $title, $desc, $piclocal, $thumblocal, $url){
	  $mysql = MySQLInstance::getInstance();
	  $mysql->runSql("SET AUTOCOMMIT=0;");	// 开始事务；
	  $sql = "insert into wx_tw_msg values(NULL, ".$type.", '".$title."', '".$desc."', '".$piclocal."', '".$thumblocal."', '".$url."', 0);";
  	  $ret = $mysql->runSql($sql);
	  if($ret == 1){
	    $tw_id = $mysql->lastId();
	  }else{
	    $tw_id = 0;
	  }
	  $mysql->runSql("COMMIT");
	  $mysql->closeDb();
	  return $tw_id;
	}
	
	// 2. 根据图文ID号返回图文消息的数据：
	// 这里只返回一行记录，数据类型为数组；
	public static function getTuwenById($tw_id){
	  $mysql = MySQLInstance::getInstance();
	  $sql = "select * from wx_tw_msg where tw_id = ".$tw_id.";";
  	  $data = $mysql->getLine($sql);
	  $mysql->closeDb();
	  return $data;
	}
	
	// 3. 返回鲜菜园的默认图文消息
	public static function getDefaultTuWen(){
	  $tw1 = Tuwen::getTuwenById(100001);
      $items = array(
        new NewsResponseItem($tw1['title'], $tw1['descri'], $GLOBALS["myf_config"]['OSS_URL'].$tw1['piclocal'], $tw1['url']));
	  return $items;
	}
	
	// 4. 根据关键字查询是否有对应title的图文消息;
	public static function searchTuWenByTitle($title){
	  $items = array();
	  $num = 0;
	  $mysql = MySQLInstance::getInstance();
	  $sql = "select * from wx_tw_msg where title like '%".$title."%' limit 3;";
	  $tws = $mysql->getData($sql);
	  if(!empty($tws)){
		foreach($tws as $tw){
		  $tw_pic = $tw['thumblocal'];		// 默认使用的是小图
		  if($num == 0){						// 第一条记录用的是大图;
		  	$tw_pic = $tw['piclocal'];
		  }
		  $items[] = new NewsResponseItem($tw['title'], $tw['descri'], $GLOBALS["myf_config"]['OSS_URL'].$tw['piclocal'], $tw['url']);
		  $num = $num + 1;
		}
		return $items;
	  }else{
	    return NULL;
	  }
	}
	
	// 5. 根据图文ID查询是否有对应tw_id的图文消息；
	// 	  返回图文消息封装好的items
	public static function searchTuWenById($tw_id){
	  $items = array();
	  $mysql = MySQLInstance::getInstance();
	  $sql = "select * from wx_tw_msg where tw_id = ".$tw_id." limit 1;";
	  $tw = $mysql->getLine($sql);
	  if(!empty($tw)){
		  $items[] = new NewsResponseItem($tw['title'], $tw['descri'], $GLOBALS["myf_config"]['OSS_URL'].$tw['piclocal'], $tw['url']);
		  return $items;
	  }else{
	    return NULL;
	  }
	}
	
	// 6. 根据图文返回标志(return_mark)查询是否有对应return_mark的图文消息；
	// 	  返回图文消息封装好的items
	public static function searchTuWenByReturnMark($return_mark){
	  $items = array();
	  $mysql = MySQLInstance::getInstance();
	  $sql = "select * from wx_tw_msg where return_mark = ".$return_mark." limit 1;";
	  $tw = $mysql->getLine($sql);
	  if(!empty($tw)){
		  $items[] = new NewsResponseItem($tw['title'], $tw['descri'], $GLOBALS["myf_config"]['OSS_URL'].$tw['piclocal'], $tw['url']);
		  return $items;
	  }else{
	    return 0;
	  }
	}

	
  }
?>
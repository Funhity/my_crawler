<?php

/**
* 数据库访问类
**/
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'RedisConnect.php';

class RedisInstance {
    
	private static $redis;

	// 构造函数(不允许创建数据库连接实例);
	public function __construct(){
//	  echo "Not allowed";
	}
	
	// 这里是连接数据库的对外接口，返回一个数据库连接对象：
	public static function getInstance($cfg = null){
	  if(!isset(self::$redis)){
		self::$redis = new RedisConnect($cfg);
	  }
	  return self::$redis; 
	}
}
?>

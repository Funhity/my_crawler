<?php

namespace Mylib\Weixin;
use Mylib\Tools\CurlTool;

  /**
  * 微信发送广播消息类
  * 1. 构造用于群发的图文消息数组
  * 2. 上传群发的图文消息；
  **/
  class WeixinWall {
    // 1. 构造用于群发的图文消息消息数组；
	// 一次群发消息可以包含多个图文消息，这里传入$news参数，该函数的作用就是往$news集合中添加元素；
	public static function addNews($news,$thumb_media_id, $title, $content, $author=null, $content_source_url=null, $digest=null, $show_cover_pic=null){ 
      $article=array();
      $article["thumb_media_id"] = $thumb_media_id;
	  if(!is_null($author)){ 
        $article["author"] = $author;
      }
      $article["title"] = urlencode($title);
      if(!is_null($content_source_url)){ 
        $article["content_source_url"] = $content_source_url;
      } 
      $article["content"] = urlencode($content);
      if(!is_null($digest)){ 
        $article["digest"] = $digest;
      } 
      if(!is_null($show_cover_pic)){ 
        $article["show_cover_pic"] = $show_cover_pic;
      } 
      $news[]=$article;
      return $news;
	}
	
	// 2. 上传用于群发的图文消息Json消息
	// 函数上传成功后返回media_id，用于群发；上传失败后返回NULL；
	public static function uploadNews($news){ 
      $url = $GLOBALS["myf_config"]['WX_API_URL']."/cgi-bin/media/uploadnews?access_token=".$GLOBALS['g_access_token'];
      $ret = Curl::curlPost($url, json_encode(array('articles' => $news)));
      $ret = json_decode($ret, true);
      return Curl::getResult($ret)?$ret['media_id']:null;
    }
	
	// 3. 根据分组群发图文消息
	public static function sendWallNewsByOids($group_id, $media_id){
	  $url = $GLOBALS["myf_config"]['WX_API_URL']."/cgi-bin/message/mass/send?access_token=".$GLOBALS['g_access_token'];
	  $mysql = MySQLInstance::getInstance();
	  $sql = "select wx_openid from customers where wx_openid !=NULL or wx_openid != '';";
  	  $openids = $mysql->getData($sql);
	  $mysql->closeDb();
	  $ids = NULL;
	  foreach($openids as $id){
	    $ids = $ids . '"' . $id['wx_openid'] . '"' . ',
';
	  }
	  $ids = substr($ids,0,strlen($ids)-3); 
	  $json = '{
					"touser":[
					  '.$ids.'
					],
					"mpnews":{
					  "media_id":"'.$media_id.'"
					},
					"msgtype":"mpnews"
				}';
      $ret = Curl::curlPost($url, $json);
	  return $ret;
      return Curl::getResult($ret)?$ret['media_id']:null;
    }
	
	// 根据图文ID发送一个图文消息
	public static function sendTuwenById($tw_id){
	  $tw = Tuwen::updateTuwenPicById($tw_id);
	  if(empty($tw)){
	    return 0;
	  }
	  $news = NULL;
	  $news = Wall::addNews($news,$tw['thumb_media_id'], $tw['title'], $tw['descri'], 'ghs21000', 'http://www.baidu.com');
	  $wall_media_id = Wall::uploadNews($news);
	  if($wall_media_id == NULL){
	    return 'Upload News Failed';
	  }
	  $ret = Wall::sendWallNewsByOids(0, $wall_media_id);
	  return $ret;
	}
	
	public static function sendTuwenById2($tw_id){
	  $ret = Wall::sendWallNewsByOids(2, 'NX59d-HScYkZA4LfVBb1KU_zGkIZ4xBSTLXZqE8zTxMSMcBblJSQShFwuH_PEmWr');
	  return $ret;
	}
 } 

?>
<?php


/**
* 模板消息管理
* 1. send: 发送模板消息;
**/
class WeixinTemplate {


	public function __construct() {
		$CI =& get_instance();
		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');
	}

	// 1. 发送模板消息, 只能内部调用
	private function send($template){
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".wechat_token();
		$ret = curl_post($url, $template);
		return $ret;
	}
	
	/**
	* 2. 向已预约用户发送直播即将开始模板消息
	**/
	public function liveBookedComming($openId, $live_title, $live_date, $live_url){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"NwgV3xG-dMw88-jbG5q-irekBfTi3wzKS-jozL5QEC4",
				"url":"'.$live_url.'",
				"data":{
						"first": {
						  "value":"您好, 您报名的直播15分钟后即将开始!"
					    },
						"keyword1": {
						  "value":"'.$live_title.'",
						  "color":"#173177"
					    },
						"keyword2": {
						  "value":"'.$live_date.'",
						  "color":"#173177"
					    },
						"remark":{
							"value":"点击进入直播间"
                   		}
				}
			}';
//	   pushlog('模板中的消息：', $template);
	  $ret = $this->send($template);
	  return $ret;
	}


	/**
	 * 3. 向用户发送报名成功模板消息
	 **/
	public function liveBookOk($openId, $live_title, $live_date, $live_url){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"b9oq3VFi9uN31kYpvX9pF2mmQQ4l7W1PIGC2tfs8Q8E",
				"url":"'.$live_url.'",
				"data":{
						"first": {
						  "value":"您好, 您已成功报名Wonder Live!"
					    },
						"keyword1": {
						  "value":"'.$live_title.'",
						  "color":"#173177"
					    },
						"keyword2": {
						  "value":"'.$live_date.'",
						  "color":"#FF0000"
					    },
						"remark":{
							"value":"点击进入直播间"
                   		}
				}
			}';
//		pushlog('模板中的消息：', $template);
		$ret = $this->send($template);
		return $ret;
	}


	/**
	 * 4. 向未预约用户用户发送直播即将开始模板消息
	 **/
	public function liveCommonComming($openId, $live_title, $live_date, $live_url){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"NwgV3xG-dMw88-jbG5q-irekBfTi3wzKS-jozL5QEC4",
				"url":"'.$live_url.'",
				"data":{
						"first": {
						  "value":"您好，即将有一场直播分享15分钟后开始!"
					    },
						"keyword1": {
						  "value":"'.$live_title.'",
						  "color":"#173177"
					    },
						"keyword2": {
						  "value":"'.$live_date.'",
						  "color":"#173177"
					    },
						"remark":{
							"value":"点击进入直播间"
                   		}
				}
			}';
//		pushlog('模板中的消息：', $template);
		$ret = $this->send($template);
		return $ret;
	}



}


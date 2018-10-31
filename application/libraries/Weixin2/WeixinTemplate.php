<?php


/**
* 模板消息管理
* 1. send: 发送模板消息;
**/

require_once 'WeixinBase.php';


class WeixinTemplate extends WeixinBase {


	public function __construct($params) {
		parent::__construct($params);
	}

	// 1. 发送模板消息, 只能内部调用
	private function send($template){
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->accessToken;
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


	/**
	 * 5. 向用户发送模板通知:
	 **/
	public function banmaOrderCreate($openId){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"N9PqvXpUQSR0YheVSuBUaFG70xZ_QbvMnDGYDuevPcY",
				"url":"http://web.dolphinlive.mobi/zebra_app/school_qrcode.html",
				"data":{
						"first": {
						  "value":"你已获得斑马英语-60天击破英语四六级课程入学资格"
					    },
						"keyword1": {
						  "value":"【斑马英语-60天击破英语四六级】"
					    },
						"keyword2": {
						  "value":"斑马英语"
					    },
						"remark":{
							"value":"点击进入课程"
                   		}
				}
			}';
		$ret = $this->send($template);
		return $ret;
	}

	public function rechargeDailyTemplate($openId){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"y_Ci1MRA3Sw8lGa7V2BbJhD7UDSxXrLefUcjAregmkg",
				"miniprogram":{
					"appid": "wxf5ead0b4d49a0d65",
					"pagepath": "/pages/index/index"
				},
				"data":{
						"first": {
						  "value":"今日充值任务来喇"
					    },
						"keyword1": {
						  "value":"日常任务----充值到斑马读书会"
					    },
						"keyword2": {
						  "value":"恭喜您获得每日充值机会"
					    },
					    "keyword3": {
						  "value":"看运气"
					    },
					    "keyword4": {
						  "value":"微信"
					    },
						"remark":{
							"value":"直接点击模版消息"
                   		}
				}
			}';
		pushlog('wx template: ', $template);
		$ret = $this->send($template);
		return $ret;
	}


	/**
	 * 斑马英语---21天口语计划, 每天向用户发送课程消息
	 * @param $openId
	 * @return string
	 */
	public function banma21SpeakDailyTemplate($openId, $live_id, $nickname, $dayth, $withUrl=false){
		$authUrl = '';
		if($withUrl == true) {
			$roomUrl = "http://web.dolphinlive.mobi/zebra_live/api_room.html?live_id=".$live_id;
			$authUrl = "http://api.dolphinlive.mobi/index.php/wechat/auth/Bm1_oauth?web_url=".urlencode($roomUrl);
		}

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"9QIC7UGa67f6bp9xofWN2hrBJmNOBpdpYidG7FDu7z0",
				"url":"'.$authUrl.'",
				"data":{
						"first": {
						  "value":"今日口语课程已更新\n每天进步一点点，遇见更好的自己^_^\n"
					    },
						"keyword1": {
						  "value":"【斑马英语-180天口语计划】"
					    },
						"keyword2": {
						  "value":"口语跟读第'.$dayth.'天"
					    },
					    "keyword3": {
						  "value":"已开始"
					    },
						"remark":{
							"value":"\n点击底部【进入 · 斑马英语】开始学习 ↓↓↓"
                   		}
				}
			}';
		pushlog('wx template: ', $template);
		$ret = $this->send($template);
		return $ret;
	}


	/**
	 * 斑马扫生办--退款通知
	 * @param $openId
	 * @return string
	 */
	public function banmaZsbPaybackMoney($openId){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"V1RPhSzkwW3Z0iDRGC-ebmvQFOJmIBtOlUUTiNw_7Qg",
				"url":"",
				"data":{
						"first": {
						  "value":"微信官方活动规则限制"
					    },
						"keyword1": {
						  "value":""
					    },
						"keyword2": {
						  "value":"3元"
					    },
					    "keyword3": {
						  "value":"2017年5月23日"
					    },
					    "keyword4": {
						  "value":"0元"
					    },
						"remark":{
							"value":"同学，你好。因为，保险活动刚收到微信官方的限制，现在将活动停止，之前你所交费用将原路退还给你。给你带来不便，多多谅解。有什么问题，都可以通过微信wonderliveapp跟我反馈。",
							"color":"#418caf"
                   		}
				}
			}';
		$ret = $this->send($template);
		return $ret;
	}




	/**
	 * 斑马英语--聊天室消息点赞通知
	 * @param $openId
	 * @return string
	 */
	public function banma21SpeakLike($nickName, $openId, $live_id){
		$url = "http://web.dolphinlive.mobi/zebra_live/api_room.html?live_id=".$live_id;

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"1xHW2nyQHDx1LH0zTMnWdOONjjDUpdEmFu8QuuhAWtU",
				"url":"'.$url.'",
				"data":{
						"first": {
						  "value":"有一个新的点赞"
					    },
						"keyword1": {
						  "value":"'.$nickName.'"
					    },
						"keyword2": {
						  "value":"'.date('Y-m-d H:i:s', time()+28800).'"
					    },
						"remark":{
							"value":"\n你的发音很不错，有小伙伴给你点赞了~",
							"color":"#418caf"
                   		}
				}
			}';
		$ret = $this->send($template);
		return $ret;
	}


	/**
	 * 斑马英语--聊天室消息@消息通知
	 * @param $openId
	 * @return string
	 */
	public function banma21SpeakAtNotice($fromNickName, $openId, $live_id, $content){
		$url = "http://web.dolphinlive.mobi/zebra_live/api_room.html?live_id=".$live_id;

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"1xHW2nyQHDx1LH0zTMnWdOONjjDUpdEmFu8QuuhAWtU",
				"url":"'.$url.'",
				"data":{
						"first": {
						  "value":"有人@了你"
					    },
						"keyword1": {
						  "value":"'.$fromNickName.'"
					    },
						"keyword2": {
						  "value":"'.date('Y-m-d H:i:s', time()+28800).'"
					    },
						"remark":{
							"value":"\n'.$content.'",
							"color":"#418caf"
                   		}
				}
			}';
		$ret = $this->send($template);
		return $ret;
	}


	/**
	 * 斑马英语--推送课程信息:
	 * @param $openId
	 * @return string
	 */
	public function banmaLessonPush($openId, $first, $title, $time, $remark, $url=''){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"6inbSD44IU2XxvVKZmjJXoxYeX2PADv0I5Zt6-H8A1E",
				"url":"'.$url.'",
				"data":{
						"first": {
						  "value":"'.$first.'"
					    },
						"keyword1": {
						  "value":"'.$title.'"
					    },
						"keyword2": {
						  "value":"'.$time.'"
					    },
						"remark":{
							"value":"'.$remark.'",
							"color":"#418caf"
                   		}
				}
			}';
		pushlog('template: ', $template);
		$ret = $this->send($template);
		return $ret;
	}


	/**
	 * 斑马英语--发送21天完成打卡消息，给用户发奖品
	 * @param $openId
	 * @return string
	 */
	public function banma21SpeakFinishGiftNotice($openId, $first, $name, $time, $remark, $url=''){

		$template = '{
				"touser":"'.$openId.'",
				"template_id":"uLSVO4A6goyEBsEofl25QeYFv2G6MeOHnBeicbYugCw",
				"url":"'.$url.'",
				"data":{
						"first": {
						  "value":"'.$first.'"
					    },
						"keyword1": {
						  "value":"'.$name.'"
					    },
						"keyword2": {
						  "value":"'.$time.'"
					    },
						"remark":{
							"value":"'.$remark.'",
							"color":"#418caf"
                   		}
				}
			}';
		pushlog('template: ', $template);
		$ret = $this->send($template);
		return $ret;
	}




}


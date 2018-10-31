<?php
/**
 * 自定义日志推送函数: 测试环境下推送到workerman, 正式环境下写到日志文件:
 */
defined('BASEPATH') OR exit('No direct script access allowed');


//require_once APPPATH.'libraries/Workerman/WorkerPush.php';

//将post或get请求输出成url
function buildRequest2Url()
{
    if (empty($_REQUEST)) {
        return '';
    }

    $out = '';
    $sp = '';
    foreach ($_REQUEST as $k => $v) {
        $out .= $sp . $k . "=" . $v;
        $sp = '&';
    }
    return $_SERVER['PATH_INFO'].'?'.$out;
}

// 写日志
function wLog($logType, $data)
{
    $accessPath = dirname(__FILE__) .'/../logs/'. $logType . "/" . date('ym') . '/';
    ! file_exists($accessPath) && mkdir($accessPath, 0777, true);

    file_put_contents($accessPath . date('d',time()+28800) . '.txt', date('H:i:s',time()+28800) . '___' . str_replace(array(
        "\n",
        "\r"
    ), '', print_r($data, true) . '___【'.buildRequest2Url().'】___【' . print_r($_REQUEST, true)) . "】\n", FILE_APPEND);
}

if ( ! function_exists('pushlog'))
{

	function pushlog($title, $msg, $level=1)
	{
	    if ($msg == 1){
	        return;
	    }
	    //改写日志
	    wLog('push', $title.'__'.print_r($msg, true));
	    return;
	    
		// 识别msg类型，然后序列化
		$msg = is_array($msg) || is_object($msg) ? print_r($msg, true) : $msg;

		$srcInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

		// 如果是在函数里调用这个函数，可能就没有class这个属性;
		$mod = '';
		$class = '';
		$srcFullClass = $srcInfo[1]['class'];
//		if(in_array('class', $srcInfo[1])) {
//			$srcFullClass = explode('\\', $srcInfo[1]['class']);
//			$mod = $srcFullClass[0];					// 调用模块
//			$class = end($srcFullClass);				// 调用类
//		}
		$func = $srcInfo[1]['function'];			// 调用函数

		// 所有环境下的日志都会写入文件, 测试环境下才会推送到workerman中;
		switch($level){
			case 1:
				$level_str = 'Debug';
				$color = '[47;30;1m';   // 白灰
				break;
			case 2:
				$level_str = 'Info';
				$color = '[42;30;1m';   // 绿
				break;
			case 3:
				$level_str = 'Notice';
				$color = '[44;39;1m';   // 蓝
				break;
			case 4:
				$level_str = 'Warn';
				$color = '[43;39;1m';   // 黄
				break;
			case 5:
				$level_str = 'Err';
				$color = '[105;39;1m';	// 粉红
				break;
			case 6:
				$level_str = 'Crit';
				$color = '[45;39;1m';	// 鲜粉红
				break;
			case 7:
				$level_str = 'Alert';
				$color = '[101;39;1m';	// 红
				break;
			case 8:
				$level_str = 'Panic';
				$color = '[41;39;1m';	// 鲜红
				break;
			default:
				$level_str = 'Unknow';
				$color = '[47;30;1m';	// 白灰
		}

		$spChar = '';
		$color = $spChar.$color;
		$right = $spChar.'[0m';
		$timeNow = time()+28800;
		$dateNow = date('Y-m-d H:i:s', $timeNow);
		$word = "$dateNow $color $level_str $right  $srcFullClass \n\e[4m$title\e[0m\n\e[1m$msg\e[0m $right\n\n";

		// 目录判断处理:
		$dir = APPPATH."logs/".date('Y-m-d', $timeNow)."/";		// 换成东八区时间
		if(!is_dir($dir)) {
			$oldmask = umask(0);		// 设置umask的值，否则无法创建777的目录
			mkdir($dir, 0777, true);
			umask($oldmask);
		}

		// #################### 记录会话日志 #########################
		if(is_cli()) {
			$sessionId = '127.0.0.1__cli';
		} else {
			if(!defined('USER_ID_LOG')) {
				// 如果不是cli模式，获取ip地址+客户端类型组合成md5值' $_SERVER['REMOTE_ADDR], $_SERVER['HTTP_USER_AGENT'] '作为会话id:
				$sessionId = $_SERVER['REMOTE_ADDR'].'__'.md5($_SERVER['HTTP_USER_AGENT']);
			} else {
				$sessionId = USER_ID_LOG;
			}
		}
		$fileName = $dir."session__".date('Y-m-d', $timeNow)."-".$sessionId.".log";
		if(!file_exists($fileName)) {
			touch($fileName);
			chmod($fileName, 0777);
		}
		$fh = fopen($fileName, "a");
		fwrite($fh, $word);
		fclose($fh);

		// #################### 记录全局日志 ##########################
		$fileName2 = $dir."message__".date('Y-m-d', $timeNow).".log";
		if(!file_exists($fileName2)) {
			touch($fileName2);
			chmod($fileName2, 0777);
		}
		$fh2 = fopen($fileName2, "a");
		fwrite($fh2, $word);
		fclose($fh2);

		// ################ 记录错误日志 Level >=3 #####################
		if($level >= 3) {
			$fileName3 = $dir."error__".date('Y-m-d', $timeNow).".log";
			if(!file_exists($fileName3)) {
				touch($fileName3);
				chmod($fileName3, 0777);
			}
			$fh3 = fopen($fileName3, "a");
			fwrite($fh3, $word);
			fclose($fh3);
		}

		return;
		// 正式环境到此为止
//		if(substr(ENVIRONMENT, 0, 4) == 'prod') return;
//
//		// 测试环境推送日志到workerman
//		$data['app'] = ENVIRONMENT;
//		$data['level'] = $level;
//		$data['cust_id'] = 100;						// 这个字段暂时用不上
//		$data['mod'] = $mod;
//		$data['class'] = $func;
//		$data['func'] = $title;
//		$data['msg'] = $msg;
//
//		$sockData = $data;
//		$sockData['msg'] = nl2br($data['msg']);

		// 即便推送出错，也不报错；
//		$oldErrorReporting = error_reporting(); // save error reporting level
//		error_reporting($oldErrorReporting ^ E_WARNING); // disable warnings
//		WorkerPush::pushLog($sockData);
//		error_reporting($oldErrorReporting); // restore error reporting level

	}
}



// 推送错误消息到公众号的管理员上面
if ( ! function_exists('wechat_warn'))
{

	function wechat_warn($order, $result, $detail='', $title='')
	{

		$CI =& get_instance();

		$CI->load->helper('wechat_token');
		$CI->load->helper('curl_get');
		$CI->load->helper('curl_post');

		$pushOpenids = [
			'oCDZpwn3gFkPux1fE3MEncweeaog',
			// 'oCDZpwlSr00w_6084HtemvHvy-Vk',		// 官海升
		];
		$weCfg = Consts::WECHAT_BMYY;

		$accessToken = wechat_token($weCfg['name']);		// 这个name定义redis的key
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accessToken;

		$ret = null;
		foreach($pushOpenids as $openid) {
			$template = '{
				"touser":"'.$openid.'",
				"template_id":"PfjRZ80kGlaEi0o3bdUlheF2TJHqwU_Ez1Ews3-0FgM",
				"url":"",
				"data":{
						"first": {
						  "value":"'.$title.'"
					    },
						"keyword1": {
						  "value":"'.$order.'"
					    },
						"keyword2": {
						  "value":"'.$result.'"
					    },
					    "keyword3": {
						  "value":"'.date('Y-m-d H:i:s', time()+28800).'"
					    },
						"remark":{
							"value":"'.$detail.'",
							"color":"#418caf"
                   		}
				}
			}';
			$ret = curl_post($url, $template);
		}
		return $ret;
	}
}

if ( ! function_exists('wlog')){
	function wlog($msg){
		$timeNow = time()+28800;
		$dateNow = date('Y-m-d H:i:s', $timeNow);

		// 目录判断处理:
		$dir = APPPATH."logs/logs_ma/".date('Y-m-d', $timeNow)."/";		// 换成东八区时间
		if(!is_dir($dir)) {
			$oldmask = umask(0);		// 设置umask的值，否则无法创建777的目录
			mkdir($dir, 0777, true);
			umask($oldmask);
		}

		$msg = is_array($msg) || is_object($msg) ? print_r($msg, true) : $msg;
		$msg = 'info：' . $msg;

		$filename = $dir . 'ci_log.log';
		if(!empty($msg)){
			$handle = fopen($filename, 'a+');
			fwrite($handle, $msg . "\r\n");
			fclose($handle);
		}
	}
}

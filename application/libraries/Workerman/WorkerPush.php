<?php

require_once 'Gateway.php';

class WorkerPush {

	// 推送日志消息到实时日志系统
	protected static $logRegisterAddress = '120.25.63.64:1208';		// 都推送到日本测试服务器
	public static function pushLog($logData){
		Gateway::$registerAddress = self::$logRegisterAddress;
		$new_message = array(
			'type'=>'log',
			'content'=>$logData,		// 推送消息
			'time'=>date('Y-m-d H:i:s'),
		);
		try{
			Gateway::sendToGroup($logData['app'], json_encode($new_message));
			return 1;		// 返回1，推送成功;
		}catch(Exception $e){

			$dir = APPPATH."logs/".date('Y-m')."/";
			if(!is_dir($dir)){
				mkdir($dir, 0777, true);
			}
			$fileName = $dir.date('d')."-workerman".".log";
			$dateNow = date('Y-m-d H:i:s');
			$word = $dateNow . " " . $e->getMessage() . "\n";
			$fh = fopen($fileName, "a");
			fwrite($fh, $word);
			fclose($fh);
		}
	}
}

?>
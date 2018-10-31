<?php
/**
 * 使用curl发送post请求函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('curl_post_form_async'))
{

	function curl_post_form_async($host, $path, $data, $ignoreRet=TRUE){
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		$content = http_build_query($data);
		if(!$fp){
			pushlog('error', 4);
			echo "$errstr ($errno)<br />\n";
		}else{
			fwrite($fp, "POST /".$path." HTTP/1.1\r\n");
			fwrite($fp, "Host: ".$host."\r\n");
			fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
			fwrite($fp, "Content-Length: ".strlen($content)."\r\n");
			fwrite($fp, "Connection: close\r\n");
			fwrite($fp, "\r\n");
			fwrite($fp, $content);
			header('Content-type: text/plain');
			if(!$ignoreRet){
				while (!feof($fp)) {
					echo fgets($fp, 1024);
				}
			}
			fclose($fp);
		}
	}
}

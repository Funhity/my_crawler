<?php
/**
 * 使用curl发送get请求函数
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('curl_get')) {

    function curl_get($url, $header = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        if ($header != null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (!curl_exec($ch)) {
            error_log(curl_error($ch));
            $data = '';
        } else {
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        return $data;
    }
}

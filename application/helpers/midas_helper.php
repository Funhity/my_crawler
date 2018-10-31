<?php


/**
 * 米大师支付签名
 * 文档 https://developers.weixin.qq.com/minigame/dev/tutorial/open-ability/midas-signature.html
 */
if (!function_exists('get_midas_sig')) {

    function get_midas_sig($uri, $method, $secret,  $data)
    {
        ksort($data);
        $str = http_build_query($data);
        $str .= "&org_loc=$uri&method=$method&secret=$secret";
        $str =hash_hmac('sha256', $str, $secret) ;
        return $str;
    }
}


/**
 * 米大师支付 开平签名
 * 文档 https://developers.weixin.qq.com/minigame/dev/tutorial/open-ability/midas-signature.html
 */
if (!function_exists('get_midas_mp_sig')) {

    function get_midas_mp_sig($uri, $method, $session_key,$access_token,  $data)
    {
        $data["access_token"] = $access_token;
        ksort($data);
        $str = http_build_query($data);
        $str .= "&org_loc=$uri&method=$method&session_key=$session_key";
        $str =hash_hmac('sha256', $str, $session_key) ;
        return $str;
    }
}
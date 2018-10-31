<?php

/**
 * 返回消息
 * @param int $ack 消息代码
 * @param string $msg 消息
 * @param array/string $data 返回数据
 * @param string $encode 是否使用自定义加密
 */
function api_output($status = 0, $msg = '', $data = array())
{
    exit(json_encode_ex(array('status' => $status, 'msg' => $msg, 'data' => $data)));
}

/**
 * @desc 关键字分词
 * @param string $keyword 关键字
 * @return array
 */
function split_keyword($keyword)
{
    if (empty($keyword) || is_array($keyword)) {
        return;
    }
    $url = 'http://www.xunsearch.com/scws/api.php';
    $data = array(
        'data' => $keyword,
        'charset' => 'utf8',
        'respond' => 'json',
        'ignore' => 'yes',
        'multi' => 0,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = (array)json_decode(curl_exec($ch));
    curl_close($ch);

    if (!is_array($keyword) && !empty($result) && $result['status'] == 'ok') {
        $keywords = array();
        foreach ($result['words'] as $v) {
            if ($v->idf > 0 && strpos($v->attr, 'n') !== false) {
                $keywords[] = $v->word;
            }
        }
        if (empty($keywords)) {
            $keywords[] = $keyword;
        }

    }
    return $keywords;
}

function create_token($len = 15)
{
    $letters = range('a', 'z');
    $letters = array_merge($letters, range('A', 'Z'));
    $letters = array_merge($letters, range(0, 9));
    shuffle($letters);
    $ret = '';
    for ($i = 0; $i < $len; ++$i)
        $ret .= $letters[mt_rand(0, 59)];
    return $ret;
}

function create_verify_code($len = 4)
{
    $letters = range(0, 9);
    shuffle($letters);
    $ret = '';
    for ($i = 0; $i < $len; ++$i)
        $ret .= $letters[mt_rand(0, 9)];
    return $ret;
}

function async_request($url, $param = array())
{
    $urlinfo = parse_url($url);
    $host = $urlinfo['host'];
    $path = $urlinfo['path'];
    $query = isset($param) ? http_build_query($param) : '';
    $port = 80;
    if (!empty($urlinfo['port'])) {
        $port = $urlinfo['port'];
    }
    $errno = 0;
    $errstr = '';
    $timeout = 10;

    $fp = fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($fp) {
        //stream_set_blocking($fp,0);//开启了手册上说的非阻塞模式
        //stream_set_timeout($fp,1);//设置超时
        $out = "POST " . $path . " HTTP/1.1\r\n";
        $out .= "host:" . $host . "\r\n";
        $out .= "content-length:" . strlen($query) . "\r\n";
        $out .= "content-type:application/x-www-form-urlencoded\r\n";
        $out .= "connection:close\r\n\r\n";
        $out .= $query;
        fputs($fp, $out);
        usleep(1000);
        fclose($fp);
        return true;
    }
    return 'open socket fail';
}

function async_curl($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function json_encode_ex($value)
{
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        $str = json_encode($value);
        $str = preg_replace_callback(
            "#\\\u([0-9a-f]{4})#i",
            function ($matchs) {
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
            },
            $str
        );
        return $str;
    } else {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

function get_client_ip($type = 0)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip [$type];
    if (isset($_SERVER ['HTTP_X_REAL_IP'])) { // nginx 代理模式下，获取客户端真实IP
        $ip = $_SERVER ['HTTP_X_REAL_IP'];
    } elseif (isset ($_SERVER ['HTTP_CLIENT_IP'])) { // 客户端的ip
        $ip = $_SERVER ['HTTP_CLIENT_IP'];
    } elseif (isset ($_SERVER ['HTTP_X_FORWARDED_FOR'])) { // 浏览当前页面的用户计算机的网关
        $arr = explode(',', $_SERVER ['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset ($arr [$pos]);
        $ip = trim($arr [0]);
    } elseif (isset($_SERVER['HTTP_REMOTEIP'])) {
        $ip = $_SERVER ['HTTP_REMOTEIP'];
    } elseif (isset ($_SERVER ['REMOTE_ADDR'])) {
        $ip = $_SERVER ['REMOTE_ADDR']; // 浏览当前页面的用户计算机的ip地址
    } else {
        $ip = $_SERVER ['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array(
        $ip,
        $long
    ) : array(
        '0.0.0.0',
        0
    );
    return $ip [$type];
}

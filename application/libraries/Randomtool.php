<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 随机工具
 */
class Randomtool {

    /**
     * 获取随机字符串
     */
    public function getRandChar($length) {
        return random_string('alnum', $length);
    }

    /**
     * 生成随机的ID, 前缀+yyyyMMddHHmmss加4位随机数字
     */
    public function getRandomId($prefix) {
        return $this->getTimeRandomId($prefix, time());
    }

    public function getTimeRandomId($prefix=null, $time=null) {
        if($prefix == null) $prefix = '';
        if($time == null) $time = time();

        $id = $prefix . date('YmdHis', $time) . mt_rand(0, 1000) . redis_sequence();       // 使用redis序列保证唯一性
        return strtoupper($id);
    }

    
    public function getRandomInt($start, $end) {
        return rand($start,$end);
    }
    
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 字符串工具
 */
class Stringtool {
    
    /**
     * 判断是否以某字符串开头
     */
    public function isStringStartWith($str, $needle) {

        return strpos($str, $needle) === 0;

    }
    
    
    
    
}
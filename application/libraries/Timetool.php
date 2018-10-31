<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 时间日期工具
 */
class Timetool {

    /**
     * 获取指定时间的15分间隔
     * @param type $time
     * @return type
     */
    public function getNear15Minute($time) {
        $tmp_str = date('YmdH', $time) . '0000';
        $res = strtotime($tmp_str);
        $sec = $time - $res;
        $s = (int) ($sec / (60 * 15));
        $ok = $res + $s * 60 * 15;
        return $ok;
    }

   
    
    public function getFriendlyTime($time, $timezone='') {

        //把目标时间。转换成utc时间
        //$utc_datetime = date('Y-m-d H:i:s', $time_old);
        //$date = new DateTime($utc_datetime, new DateTimeZone('UTC'));
        //$time = $date->getTimestamp();

        $now = time();

        //未来
        if ($time > $now) {
            
            if($this->isToday($time)) {
                return $this->formatTimeString($time, 'H:i', $timezone);
                
            } else {
                $one = $this->getDayTime00($time);
                $two = $this->getDayTime00(time());
                $left = (int)  (($one-$two)/(60*60*24));
                if($left<2) {
                    return lang_format('str_comm_time_dayLater', '1天後');
                }
                return lang_format('str_comm_time_daysLater', '天後', $left);
            }
            
            
        }
        //过去或者现在 
        else {

            $left = $now - $time;

            //判断是否在1分钟以内，返回刚刚
            if ($left < 1 * 60 * 1) {
                return lang_format('str_comm_time_justNow', '刚刚', $left);
            }

            //小于一小时
            if ($left < 1 * 60 * 60) {

                $min = (int) ($left / 60);

                if ($min == 1) {
                    return lang_format('str_comm_time_minuteAgo', '1分钟前', $left);
                } else {
                    return lang_format('str_comm_time_minuteAgo', 'n分钟前', $left);
                }
            }


            //大于一小时
            $left_hour = (int) ($left / (60 * 60));

            //24小时以内
            if ($left_hour < 24) {

                if ($left_hour == 1) {
                    return lang_format('str_comm_time_hourAgo', '1小时前', $left_hour);
                } else {
                    return lang_format('str_comm_time_hoursAgo', 'n小时前', $left_hour);
                }
            }


            if ($this->isYesterday($time)) {
                return lang_format('str_comm_time_yesterday', '昨天');
            }

            //天数
            $left_day = (int) ($left_hour / 24);
            if ($left_day <= 31) {
                if ($left_day == 1) {
                    return lang_format('str_comm_time_hoursAgo', '1天前', $left_day);
                } else {
                    return lang_format('str_comm_time_daysAgo', 'n天前', $left_day);
                }
            }


            $left_month = (int) ($left_day / 30);

            if ($left_month <= 12) {
                if ($left_month == 1) {
                    return lang_format('str_comm_time_monthAgo', '1个月前', $left_month);
                } else {
                    return lang_format('str_comm_time_monthsAgo', 'n个月前', $left_month);
                }
            }


            $left_year = (int) ($left_month / 12);


            if ($left_year == 1) {
                return lang_format('str_comm_time_yearAgo', '1年前', $left_year);
            } else {
                return "$left_year years ago";
            }
        }
        if ($timezone == '') {
            return date('m/d/Y H:i', $time);
        }
        return $this->formatTimeString($time, 'm/d/Y H:i', $timezone);
    }

    //判断是否是今天
    public function isToday($time) {
        $now = date('Ymd', time());
        $t = date('Ymd', $time);
        if ($now === $t) {

            return true;
        }
        return false;
    }

    //判断是否是昨天
    public function isYesterday($time) {
        $yesterday = date('Ymd', time() - 1 * 60 * 60 * 24);
        $t = date('Ymd', $time);
        if ($yesterday === $t) {

            return true;
        }
        return false;
    }

    public function getTime($time_str, $format) {
        $date = DateTime::createFromFormat($format, $time_str);
        return $date->getTimestamp();
    }

    
    public function getTimeYesterday00($time) {
        
        //减24小时
        $t = $time-60*60*24;
        $yes = date('Y-m-d', $t).' 00:00:00';
        return $this->getTime($yes, 'Y-m-d H:i:s');
    }
    
    public function getTimeYesterday24($time) {
        
        //减24小时
        $t = $time-60*60*24;
        $yes = date('Y-m-d', $t).' 24:00:00';
        return $this->getTime($yes, 'Y-m-d H:i:s');
    }
    
    
    
    /**
     * 获取昨天0点时间
     */
    public function getYesterday00(){
        
        $yes = date("Y-m-d",strtotime("-1 day")).' 00:00:00';
        
        return $this->getTime($yes, 'Y-m-d H:i:s');
    }
    
    
    /**
     * 格式化时间字符串
     * @param type $time
     * @param type $format
     * @param type $to_timezone
     */
    public function formatTimeString($time, $format, $to_timezone='') {
        try {
            $utc_datetime = date('Y-m-d H:i:s', $time);
            $date = new DateTime($utc_datetime, new DateTimeZone('UTC'));
            if(!empty($to_timezone)) {
                $date->setTimezone(new DateTimeZone($to_timezone));
            }
            return $date->format($format);
        } catch (Exception $e) {
            //echo $e->getMessage();
        }
        return '';
    }
    
    /*
     * 时区转换
     */
    public function toTimeZoneRealAdd($time, $to_timezone) {
        try {
            $utc_datetime = date('Y-m-d H:i:s', $time);
            $date = new DateTime($utc_datetime, new DateTimeZone('UTC'));

            $date->setTimezone(new DateTimeZone($to_timezone));

            return $this->getTime($date->format('Y-m-d H:i:s'), 'Y-m-d H:i:s');
        } catch (Exception $e) {
            //echo $e->getMessage();
        }
        return $time;
    }

    /**
     * 时间增加月份（固定值31天）
     * @param type $time
     * @param type $month
     */
    public function addMonth($time, $month) {
        $tmp_str = date('YmdHis', $time);
        return strtotime("$tmp_str +$month month");
    }

    /**
     * 获取一个时间当天凌晨0点的时间
     * @param type $time
     */
    public function getDayTime00($time) {
        $tmp_str = date('Ymd', $time) . '000000';
        return strtotime($tmp_str);
    }

    /**
     * 获取一个时间当天24点的时间
     * @param type $time
     */
    public function getDayTime24($time) {
        $tmp_str = date('Ymd', $time) . '240000';
        return strtotime($tmp_str);
    }

}

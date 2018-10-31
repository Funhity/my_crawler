<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 价格工具
 */
class Pricetool {
    
    
    const PRICE_99 = 99;
    const PRICE_199 = 199;
    const PRICE_399 = 399;
    const PRICE_599 = 599;//弃用
    const PRICE_699 = 699;
    const PRICE_999 = 999;
    const PRICE_1999 = 1999;
    const PRICE_3999 = 3999;
    const PRICE_5999 = 5999;
    const PRICE_9999 = 9999;
    
    /**
     * 返回可用的价格列表(美分，整数)
     */
    public function getAvailablePrice() {
        $price = array(
            Pricetool::PRICE_99, 
            Pricetool::PRICE_199, 
            Pricetool::PRICE_399, 
            Pricetool::PRICE_699, 
            Pricetool::PRICE_999, 
            Pricetool::PRICE_1999, 
            Pricetool::PRICE_3999, 
            Pricetool::PRICE_5999, 
            Pricetool::PRICE_9999);
        return $price;
    }
    
    /**
     * 返回可用的价格列表(美元，有小数点)
     */
    public function getAvailableDollarPrice() {
        $dollars = array();
        $price = $this->getAvailablePrice();
        for($i=0;$i<count($price);$i++) {
            array_push($dollars,  round($price[$i]/100.0, 2).'');//变成美元 
        }
        return $dollars;
    }
    
    
    
    /**
     * 美分转美元
     * @param type $cent
     */
    public function centParseDollar($cent) {
        return round( $cent/100.0,2);
    }
    
    
    /**
     * 美元转美分
     * @param type $dollar
     * @return type
     */
    public function dollarParseCent($dollar) {        
        return (int) ($dollar*1000/10);
    }
    
    
    
    /**
     * 判断价格是否是在可用范围内(美分来算)
     */
    public function isPriceAvailable($price) {
        
        //如果是免费范围内的
        if($price<1) {
            return true;
        }
        
        $items = $this->getAvailablePrice();
        for($i=0;$i<count($items);$i++) {
            //$left = $price-$items[i];
           // echo $items[$i].'==='.$price;
            if($items[$i]==$price) {
                return true;
            }
        }
        return false;
        
    }
    
    
    /**
     * 获取偷听的价格（美分，整数）
     */
    public function getListenPrice() {
        return Pricetool::PRICE_99;
    }
    
    
    /**
     * 获取默认提问的价格（美分，整数）
     */
    public function getDefaultAskPrice() {
        return Pricetool::PRICE_99;
    }
    
}
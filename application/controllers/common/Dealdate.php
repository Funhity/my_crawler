<?php
/**
 * 日期类
 */
class Dealdate{
    /**
    * +---------------------------------------------------------
    * 获取当前时间的毫秒数
    * +---------------------------------------------------------
    **/
    public function getMillisecond(){
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1) + floatval($t2)) * 1000);
    }

    protected function isCheckToken() {
        return false;
    }
}

?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    
      
    /**
     * 显示所有的返回码
     */
    protected function showAllCode() {
        date_default_timezone_set('UTC');  
        
        $time  = time();
        $this->echoCode('Running Environment ', ENVIRONMENT.'');
        $this->echoCode('UTC time Now ', $time.'');
        $this->echoCode('UTC time Now ', date('Y-m-d H:i:s', $time) );
        $timezone = $this->getParamTimezone();
        $this->echoCode('China Timezone', $timezone); 
        $this->echoCode('CN time Now ', $this->timetool->formatTimeString($time, 'Y-m-d H:i:s', $timezone));
        
        $this->echoCode('Apple Pay URL', Consts::APPLE_PAY_CHECK_URL);
        $this->echoCode('Server URL ', Consts::QA_SERVER_URL);
        $this->echoCode('PayPal Pay URL ', Consts::PAYPAL_CREATE_ORDER_PAY_URL);
        $this->echoCode('PayPal Email', Consts::PAYPAL_SHANGHU_EMAIL);
        $this->echoCode('Logs ', Consts::LOGS_PATH);
        $this->echoCode('Mysql Database ', $this->db->hostname);
        
        if(extension_loaded('redis')){
            $this->echoCode('Redis Module ', 'on');
            try{
                $this->cache->redis->save('foo', 'bar', 10);
                $this->echoCode('Redis Connect State ', 'OK');
            }catch(Exception $e) {
                $this->echoCode('Redis Connect State ', 'ERROR_'.$e->getMessage());
            }
        }else {
            $this->echoCode('Redis Module ',  'off');
        }
    }
    
    
    protected function processForUser($isLogin, $me, $token_type) {
        echo '<center><h1>Wonder Api Server</h1>';
        echo '<table border=3px width=60%>';
        echo '<tr><th width=100px>key</th><th>val</th><tr>';
        $this->showAllCode();
        echo '</table></center>';
    }
    
    
    
    
    
    private function echoCode($code, $detail) {
        echo '<tr><td>'.$code.'</td><td>'.$detail.'</td></tr>';
    } 
    
    
    
    
    
    protected function isCheckToken() {
        return false;
    }
    
    
}

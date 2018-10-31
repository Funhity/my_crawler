<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_web_log extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        $this->load->model('web_log');
                
        $this->web_log->createTable();
        
        $this->showLastSQL();
        
    }
    
    protected function initTestData() {
        $data  =array();
        return $data;
    }
    
    
    protected function isCheckToken() {
        return false;//不进行检测
    }
}
?>

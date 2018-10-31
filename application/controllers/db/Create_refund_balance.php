<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_refund_balance extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        $this->load->model('refund_balance');
        
        $this->refund_balance->createTable();
        
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

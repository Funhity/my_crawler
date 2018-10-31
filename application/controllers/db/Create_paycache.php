<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_paycache extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        
        $this->paycache->createTable();
        
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

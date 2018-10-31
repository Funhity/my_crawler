<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_collection extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        
        $this->load->model('rss/collection');
        
        $this->collection->createTable();
        
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

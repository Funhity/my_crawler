<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_media_user_view extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        
        $this->load->model('rss/media_user_view');
        
        $this->media_user_view->createTable();
        
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

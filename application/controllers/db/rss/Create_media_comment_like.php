<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_media_comment_like extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        
        $this->load->model('rss/media_comment_like');
        
        $this->media_comment_like->createTable();
        
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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_live_room_msg extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        $this->loadModel('db/live_room_msg');
        
        $this->live_room_msg->createTable();
        
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

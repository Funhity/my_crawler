<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_question_extra extends MY_Controller {

     protected function processForUser($isLogin, $me, $token_type) {
        
        $this->load->model('question_extra');
        $this->question_extra->createTable();
        
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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_profession_cate extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {
        
        $this->load->model('user/profession_cate');
        
        
        $this->profession_cate->createTable();
        
        $this->showLastSQL();
        
    }
    
    protected function isCheckToken() {
        return false;//不进行检测
    }
}
?>

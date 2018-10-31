<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once('Smarty/smarty.class.php');
require(APPPATH.'libraries/Smarty/Smarty.class.php');
class Tp extends Smarty{

//function tp(){
//
//parent::Smarty();
//
//$this->template_dir = APPPATH.'views';
//
//$this->compile_dir = APPPATH.'templates_c/';
//
//$this->left_delimiter = '<{';
//
//$this->right_delimiter = '}>';
//
//}


/**
      * ���캯��
      *
      * @access public
      * @param array/string $template_dir
      * @return obj  smarty obj
      */
    function __construct()
    {    
          parent::__construct();
 
          // $this->Smarty();
 
          //ROOT��Codeigniter������ļ�index.php����ı�webӦ�õĸ�Ŀ¼
          //������ļ��м���define('ROOT', dirname(__FILE__);
          $this->template_dir = APPPATH.'views/admin';
          $this->compile_dir = APPPATH.'templates_c/';
          //$this->config_dir   =  ROOT . '/config';
         // $this->cache_dir    =  ROOT . '/cache';
          $this->left_delimiter = '<{';

          $this->right_delimiter = '}>';
         
    }
   

}
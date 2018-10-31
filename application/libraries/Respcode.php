<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Respcode {
	/**
	 * 返回码：正确
	 */
	const CODE_OK = "0";
   
        /**
	 * 返回码：网络异常，请重试
	 */
	const CODE_ERROR_NETWORK = "101";
        
        
        /**
	 * 返回码：参数为空
	 */
	const CODE_ERROR_EMPTY_PARAM = "102"; 
        
        /**
         * 返回码：请求太频繁
         */
        const CODE_ERROR_REQ_TOO_MUCH = "103";
        
        
        /**
         * 返回码： 外部系统对接错误
         */
        const CODE_ERROR_OUTSIDE = "104";
        
        /**
         * 返回码： token已过期，请重新登录
         */
        const CODE_ERROR_TOKEN= "105";        
        
        /**
         * 返回码： 找不到相关用户信息
         */
        const CODE_ERROR_USER_NOT_FOUND = "106";           


        /**
         * 返回码： 格式不符合要求
         */
        const CODE_ERROR_FORMATE = "107";    

        /**
         * 返回码： 错误的操作
         */
        const CODE_ERROR_OPTION = "108";

        /**
         * 返回码： 找不到资源，404
         */
        const CODE_ERROR_RES_NOT_FOUND = "109";  
        
        
        /**
         * 返回码： 服务器内部环境错误
         */
        const CODE_ERROR_SYS_ERROR = "110";         



        /**
         * 返回码： 订单已经支付过了
         */
        const CODE_ERROR_ODER_HAS_PAID = "111";           


        /**
        *  参数错误
        */
        const CODE_ERROR_PARAMS = "120";

        /**
         * 执行失败
         */
        const CODE_ERROR_HANDLE_FAILED = "121";
}
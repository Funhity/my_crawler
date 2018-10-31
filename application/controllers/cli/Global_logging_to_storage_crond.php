<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @api {post} cli/global_logging_to_storage_crond 01. 日志保存
 * @apiName cli_global_logging_to_storage_crond
 * @apiVersion 1.0.0
 * @apiGroup System
 *
 * @apiDescription 把缓存中的日志信息同步到数据库中及s3存储中<br />
 * 数据是以pop的方式从缓存队列中清除的，因此即便保存失败，缓存也会被清除掉< br />
 * 脚本以Linux计划任务的方式执行:
 * <code>*\/10 * * * * /opt/php56/bin/php /data/vg02_lv01/web/tw_wonder_server/webroot/index_cli.php cli/Global_logging_to_storage_crond >> /data/vg02_lv01/web/tw_wonder_server/application/logs/Global_logging_to_storage_crond.log</code>
 */



require_once APPPATH.'vendor/autoload.php';

/**
 * 把缓存中的日志数据 pop 到aws存储上去;
 */
class Global_logging_to_storage_crond extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        if(!is_cli()) $this->respError('code_3000001', '只能从命令行访问');

        $this->load->helper('s3_newkey');
        $s3 = new \Aws\S3\S3Client(Consts::S3_CFG);

        $key = ENVIRONMENT.':LOGGING:api_logging';
        $redis = RedisInstance::getInstance();

        $timeStart = time();
        $dateNow = date('YmdHis');
        $year = date('Y');
        $incr = 1000000000;
        $keyPrefix = 'admin/logging/'.ENVIRONMENT.'/common_api/'.$year.'/'.$dateNow;
        while(true) {
            $data = $redis->rpop($key);
            if($data == false) {
                break;
            }
            $incr = $incr + 1;
            $s3Key = $keyPrefix.$incr.'.json';
            $cdnPath = null;
            try {
                $s3->putObject([
                    'Bucket'        =>  Consts::S3_BUCKET,
                    'Key'           =>  $s3Key,
                    'Body'          =>  $data,
                    'ContentType'   =>  'text/plain',
                ]);
            } catch (\Aws\S3\Exception\S3Exception $e) {
                break;
            }

            // 把日志也放到数据库:
            $logArray = json_decode($data, true);
            $new['type'] = 'api';
            if(!empty($logArray['client_id'])) $new['client_id'] = $logArray['client_id'];
            if(!empty($logArray['user_id'])) $new['user_id'] = $logArray['user_id'];
            if(!empty($logArray['type'])) $new['type'] = $logArray['type'];
            if(!empty($logArray['action'])) $new['action'] = $logArray['action'];
            if(!empty($logArray['HTTP_REFERER'])) $new['request_page'] = $logArray['HTTP_REFERER'];

            $new['datetime'] = $logArray['REQUEST_TIME'];
            $userAgent = strtolower($logArray['HTTP_USER_AGENT']);
            $platform = 'web';
            if(substr($userAgent, 0, 6) == 'okhttp') {
                $platform = 'aos';
            } else if(strpos($userAgent, 'cfnetwork') > -1) {
                $platform = 'ios';
            }
            $new['platform'] = $platform;
            $new['data_text'] = $data;
            $new['request_uri'] = $logArray['PATH_INFO'];
            if(!empty($logArray['live_id'])) $new['comment'] = $new['comment'] . '?live_id='.$logArray['live_id'];
            $this->db->insert('t_logging', $new);
        }
        $timeUsed = time() - $timeStart;

        echo "执行开始时间: " . $year.$dateNow . ", 执行时长: " . $timeUsed . ", 操作记录数: " . ($incr - 1000000000) . "\n\n";
    }

    protected function isCheckToken() {
        return false;
    }

    // 是否记录当前api的日志
    protected  function isLogginApi() {
        return false;
    }

}

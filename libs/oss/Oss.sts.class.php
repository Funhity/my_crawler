<?php
/**
 * 阿里云oss
 * @link https://help.aliyun.com/document_detail/31920.html?spm=5176.doc32044.2.6.cR9UBi
 */
include_once dirname(__FILE__) . '/sts-server/aliyun-php-sdk-core/Config.php';
use Sts\Request\V20150401 as Sts;

//
class OssStsClass
{

    function read_file($fname)
    {
        $content = '';
        if (! file_exists($fname)) {
            return false;
        }
        $handle = fopen($fname, "rb");
        while (! feof($handle)) {
            $content .= fread($handle, 10000);
        }
        fclose($handle);
        return $content;
    }

    function sts()
    {
        $content = $this->read_file(dirname(__FILE__) . '/cfg.oss.json');
        if (empty($content)) {
            return false;
        }
        $myjsonarray = json_decode($content);
        
        $accessKeyID = $myjsonarray->AccessKeyID;
        $accessKeySecret = $myjsonarray->AccessKeySecret;
        $roleArn = $myjsonarray->RoleArn;
        $tokenExpire = $myjsonarray->TokenExpireTime;
        $policy = $this->read_file(dirname(__FILE__) . "/sts-server/policy/bucket_js_upload.txt");
        
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $accessKeyID, $accessKeySecret);
        $client = new DefaultAcsClient($iClientProfile);
        
        $request = new Sts\AssumeRoleRequest();
        $request->setRoleSessionName("client_name");
        $request->setRoleArn($roleArn);
        $request->setPolicy($policy);
        $request->setDurationSeconds($tokenExpire);
        $response = $client->doAction($request);
        
        $rows = array();
        $body = $response->getBody();
        $content = json_decode($body);
        $rows['status'] = $response->getStatus();
        if ($response->getStatus() == 200) {
            $rows['AccessKeyId'] = $content->Credentials->AccessKeyId;
            $rows['AccessKeySecret'] = $content->Credentials->AccessKeySecret;
            $rows['Expiration'] = $content->Credentials->Expiration;
            $rows['SecurityToken'] = $content->Credentials->SecurityToken;
        } else {
            return false;
        }
        return $rows;
    }
}


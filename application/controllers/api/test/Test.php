<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
// 引入父类
require dirname(__FILE__) . '/DGMN_Controller.php';

class Test extends DGMN_Controller
{
    private $purifier;

    protected function processForUser($isLogin, $me, $token_type)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'GBK'); // UTF-8或者GBK
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // 这是比较好的
        $this->purifier = new HTMLPurifier ($config);


        $this->load->helper('curl_get');
        $urls = [
//            "http://www.12306.cn/mormhweb/kyyyz/ky_heb/201001/t20100124_1161.html",
//            "http://www.12306.cn/mormhweb/kyyyz/shenyang/201001/t20100124_1164.html",
//            "http://www.12306.cn/mormhweb/kyyyz/beijing/201001/t20100124_1166.html",
//            "http://www.12306.cn/mormhweb/kyyyz/taiyuan/201001/t20100124_1168.html",
//            "http://www.12306.cn/mormhweb/kyyyz/heht/201001/t20100124_1170.html",
//            "http://www.12306.cn/mormhweb/kyyyz/zhengzhou/201001/t20100124_1172.html",
//            "http://www.12306.cn/mormhweb/kyyyz/wuhan/201001/t20100124_1174.html",
//            "http://www.12306.cn/mormhweb/kyyyz/xian/201001/t20100124_1176.html",
//            "http://www.12306.cn/mormhweb/kyyyz/jinan/201001/t20100124_1178.html",
//            "http://www.12306.cn/mormhweb/kyyyz/shanghai/201001/t20100124_1180.html",
//            "http://www.12306.cn/mormhweb/kyyyz/nanchang/201001/t20100124_1182.html",
//            "http://www.12306.cn/mormhweb/kyyyz/guangtie/201001/t20100124_1184.html",
//            "http://www.12306.cn/mormhweb/kyyyz/nanning/201001/t20100124_1186.html",
//            "http://www.12306.cn/mormhweb/kyyyz/chengdu/201001/t20100124_1188.html",
//            "http://www.12306.cn/mormhweb/kyyyz/kunming/201001/t20100124_1190.html",
//            "http://www.12306.cn/mormhweb/kyyyz/lanzhou/201001/t20100124_1192.html",
//            "http://www.12306.cn/mormhweb/kyyyz/wlmq/201001/t20100124_1194.html",
//            "http://www.12306.cn/mormhweb/kyyyz/qinzang/201001/t20100124_1196.html",
        ];
        $data = [];
//        foreach ($urls as $url) {
//            $content = curl_get($url);
//
//            preg_match_all('/<td.+width="90".+>(.+)<\/td>/', $content, $all);
//            foreach ($all[1] as $v) {
//                if(!empty($v)&&$v!="√"&&$v!="&nbsp;"&&$v!="&nbsp;</span>"&&$v!="</span>"){
//                    echo "\"$v\"," . PHP_EOL;
//                }
//            }
//        }
        $this->doLine();
    }

    function doStation()
    {
        $url = "http://www.jt2345.com/huoche/zhan/";
        $content = file_get_contents($url);

        preg_match_all('/<a.*(\/huoche\/checi\/.*htm).*>(.+)<\/a>/', $content, $all);

    }

    function longitudeAndLatitudeTpPlainXY(){

    }
    /**
     * @author liaolingjia(liaolingjia@163.com)
     * @data 2018-10-30 19:31
     * @version
     * @return void
     */
    function doLine()
    {

        $url = "http://www.jt2345.com/huoche/checi/";
        $content = file_get_contents($url);
//        $content = file_get_contents(__DIR__ . "/ttt.html");
//        $content = $this->purifier->purify($content);
        preg_match_all('/<a.*?(\/huoche\/checi\/.*?htm).*?>(.+?)<\/a>/', $content, $all);
        foreach ($all[2] as $key => $v) {
            echo $v . ">>>begin  <br/>";
            if (!preg_match("/^[CDG]+.*/", $v)) {
                continue;
            }
            //查询
            $sql = "select * from dgmn_llj_station where checi ='$v'";
            $res =           $this->idiom_db->query($sql)->row_array();
            if(!empty($res)){
                continue;
            }
            $uri = $all[1][$key];
            $url = "http://www.jt2345.com$uri";
            $content = file_get_contents($url);
//            $content = iconv("gb2312", "utf-8", $content);
            $content =   mb_convert_encoding($content,"utf-8","gb2312");
            $content = str_replace(array("\r\n", "\r", "\n"), "", $content);
            preg_match_all(
                '/<tr.*?align="center".*?bgcolor="#ffffff".*?onmouseout="this.style.background=\'#FFFFFF\'".*?><td.*?height=30>(.*?)<\/td><td><a.*?href=.*?>(.*?)<\/a><\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><td>(.*?)<\/td><\/tr>/',
                $content,
                $sub_all);
            if (count($sub_all) == 7) {
                foreach ($sub_all[1] as $skey => $sv) {
                    $insert_data = [
                        "seq" => $sv,
                        "checi" => $v,
                        "name" => preg_match("/.*<.*/",$sub_all[2][$skey])?"":$sub_all[2][$skey],
                        "dao_da" => $sub_all[3][$skey],
                        "kai_shi" => $sub_all[4][$skey],
                        "ting_liu" => $sub_all[5][$skey],
                        "li_chen" => $sub_all[6][$skey],
                    ];
                    $this->idiom_db->insert('dgmn_llj_station', $insert_data);
                }
            } else {
                echo $v . "000000error <br/>";
            }

            echo $v . "------end  <br/>";
        }
    }

}


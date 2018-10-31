<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * @api {post} wechat/wechat_material_list 02. 公众号永久素材列表
 * @apiName wechat_api_wechat_material_list
 * @apiVersion 1.0.0
 * @apiGroup WechatMp
 *
 * @apiDescription 列出公众号中上传的永久素材列表<br />
 *      这个接口可以直接浏览器打开调用，不需要授权；
 * <br />
 * @apiParam {String="image，video,voice,news"} type="image" 默认为按时间倒序排序，未来的文章放在前面，已过去的时间放在后面
 *
 */

/**
 * 显示公众号上的资源列表
 */
class Wechat_material_list extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        $type = $this->getParam2('type');
        if(empty($url)) {
            $type = 'image';
        }

        $mp = $this->getParam2('mp');

        $weCfg = null;
        switch($mp) {
            case 'BMYY':
                $weCfg = Consts::WECHAT_BMYY;
                break;
            case 'BM1':
                $weCfg = Consts::WECHAT_BM1;
                break;
            case 'MP1':
                $weCfg = Consts::WECHAT_MP1;
                break;
        }
        if($weCfg == null) {
            $this->respError(500, '未传递公众号参数');
        }

        // type=image/video/voice/news
        $this->load->library('Weixin2/WeixinMaterial', array('weCfg' => $weCfg));
        $ret = $this->weixinmaterial->getMaterialList($type);     // type=image/video/voice/news
        echo $ret;
    }

    protected function isCheckToken() {
        return false;
    }

}

?>

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
class Test1 extends MY_Controller {

    protected function processForUser($isLogin, $me, $token_type) {

        $weCfg = Consts::WECHAT_BM1;
        pushlog('weCfg: ', $weCfg);
        $this->load->library('Weixin2/WeixinTemplate', array('weCfg' => $weCfg));
        pushlog('library loaded..', '');
        $this->weixintemplate->banmaOrderCreate('oC15VwKX-RLpqj6SgbxRd56cgzow');
    }

    protected function isCheckToken() {
        return false;
    }

}

?>

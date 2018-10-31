<?php


/**
 * 微信用户管理类：
 * 1. getGroups: 查询用户分组;
 * 2. newGroup: 创建新的用户组;
 * 3. moveUserGroup:　移动用户到某个分组;
 * 4. getUserGroupById: 根据openId获取用户所在分组信息;
 * 5. renameGroupById: 根据组ID更新组名;
 * 6. getUserInfoById: 根据组ID来获取该组下的用户信息;
 * 7. getUserList: 获取用户列表;
 **/
class WeixinUser
{

    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->helper('wechat_token');
        $CI->load->helper('curl_get');
        $CI->load->helper('curl_post');
    }

    // 1. 获取微信用户组：
    public static function getGroups()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=" . wechat_token();
        $content = curl_get($url);
        return $content;
    }

    // 2. 创建新的微信用户组：
    public static function newGroup($name)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=" . wechat_token();
        // 为了可以处理中文，这里要对name进行urlencode,
        // 返回的结果数据也要使用urldecode.
        $json = json_encode(
            array(
                'group' => array('name' => urlencode($name))
            )
        );
        $content = curl_post($url, $json);
        return $content;
    }

    // 3. 移动用户到某个分组：
    public static function moveUserGroup($fromusername, $groupId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=" . wechat_token();
        // 为了可以处理中文，这里要对name进行urlencode,
        // 返回的结果数据也要使用urldecode.
        $json = json_encode(
            array(
                'openid' => $fromusername,
                'to_groupid' => $groupId
            )
        );
        $content = curl_post($url, $json);
        return $content;
    }

    // 4. 根据用户的OpenID获取用户所在的组信息：
    public static function getUserGroupById($openId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=" . wechat_token();
        // 为了可以处理中文，这里要对name进行urlencode,
        // 返回的结果数据也要使用urldecode.
        $json = json_encode(
            array('openid' => $openId)
        );
        $content = curl_post($url, $json);
        return $content;
    }

    // 5. 根据组ID来更新组名：
    public static function renameGroupById($gid, $name)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token=" . wechat_token();
        // 为了可以处理中文，这里要对name进行urlencode,
        // 返回的结果数据也要使用urldecode.
        $json = json_encode(
            array(
                'group' => array(
                    'id' => $gid,
                    'name' => urlencode($name)
                )
            )
        );
        $content = curl_post($url, $json);
        return $content;
    }

    // 6. 根据用户ID来获取用户信息：
    public static function getUserInfoById($openId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . wechat_token() . "&openid=" . $openId;
        $content = curl_get($url);
        return $content;
    }

    // 7. 获取用户列表：
    public static function getUserList($next_id = '')
    {
        $extend = '';
        if (!empty($next_id)) {
            $extend = "&next_openid=$next_id";
        }
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=" . wechat_token() . $extend;
        $content = curl_get($url);
        return $content;
    }
}

?>
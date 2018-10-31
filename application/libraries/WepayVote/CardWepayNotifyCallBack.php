<?php

require_once "WxPay.Notify.php";
require_once "WxPay.Api.php";
require_once "WxPay.Notify.php";


/**
 * 小程序支付--支付回调处理类
 */
class CardWepayNotifyCallBack extends WxPayNotify {

    /**
     * 查询订单
     */
    public function Queryorder($transaction_id) {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    /**
     * 重写回调处理函数: 返回true, 则通知微信回调成功；返回false则通知微信回调失败;
     */
    public function NotifyProcess($data, &$msg)
    {

        pushlog("收到支付回调数据:", $data);
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            pushlog("支付回调输入参数不正确", 'transaction_id', 4);
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            pushlog("支付回调订单查询失败,", '订单信息不正确', 4);
            return false;
        }
        pushlog('微信支付回调数据检查通过', $data['out_trade_no']);
        $order_id = $data['out_trade_no'];
        $pay_account= $data['openid'];
        $pay_price = $data['total_fee'];
        $pay_type = Paycache::PAY_TYPE_BUSINESS_CARD;
        $CI =& get_instance();
        $is_ok = $CI->paycache->orderPaid($order_id, $pay_account, $pay_price, $pay_type);
        
        $order = $CI->paycache->getPaycache($order_id);
        $order_id = $order->order_id;
        $user_id = $CI->wechat_user->getUserId($pay_account);
        $balance = $order->order_price - $order->pay_price;
        $outResult = $CI->refund_balance->tryCreateOut($order_id, $user_id, $balance);

        if (!$is_ok) {
            pushlog('微信支付回调失败，更新数据失败', $data['out_trade_no'], 5);
            return false;
        }

        $card_id = $CI->weapp_card_order->getCardId($order_id);
        $alloction_result = $CI->weapp_card_packet->alloction($card_id);
        if($alloction_result){
            pushlog('分配成功', $card_id);
        }else{
            pushlog('分配失敗', $card_id);
        }
        pushlog('微信支付回调成功', $data['out_trade_no']);
        return true;
    }

}
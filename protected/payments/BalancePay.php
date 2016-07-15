<?php

/**
 * 预付款支付
 *
 * @author baihua <baihua_2011@163.com>
 */
class BalancePay extends BasePay {

    /**
     * 获取提交地址
     * @return type
     */
    public function getSubmitUrl() {
        return Yii::app()->getController()->createAbsoluteUrl('ucenter/paymentBalance');
    }

    /**
     * 获取要发送的数据数组结构
     * @param type $paymentInfo
     * @return type
     */
    public function getSendData($paymentInfo) {
        $userId = Yii::app()->getController()->_userI['userId'];

        $return['attach'] = $paymentInfo['M_Paymentid'];
        $return['total_fee'] = $paymentInfo['M_Amount'];
        $return['order_no'] = $paymentInfo['M_OrderNO'];
        $return['return_url'] = $this->callbackUrl;
        $return['sign'] = Common::getSign($return, $userId . $paymentInfo['M_PartnerKey']);

        return $return;
    }

    /**
     * 同步支付回调
     * @param type $ExternalData
     * @param type $paymentId
     * @param type $money
     * @param type $message
     * @param type $orderNo
     */
    public function callback($ExternalData, &$paymentId, &$money, &$message, &$orderNo) {
        $partnerKey = Payment::getConfigParam($paymentId, 'M_PartnerKey');
        $userId = Yii::app()->getController()->_userI['userId'];
        if (!$ExternalData['order_no'] || !$ExternalData['total_fee'] || !$ExternalData['sign']) {
            $message = '缺少必要参数';
            return false;
        }
        $orderNo = $ExternalData['order_no'];
        $money = $ExternalData['total_fee'];
        $ExternalData['return_url'] = urlencode($ExternalData['return_url']);
        $oldSign = $ExternalData['sign'];
        $newSign = Common::getSign($ExternalData, $userId . $partnerKey);
        if ($oldSign == $newSign) {
            //支付单号
            switch ($ExternalData['is_success']) {
                case 'T': {
                        $this->recordTradeNo($orderNo, $orderNo);
                        return true;
                    }
                    break;

                case 'F': {
                        return false;
                    }
                    break;
            }
        } else {
            $message = '校验码不正确';
        }
        return false;
    }

    /**
     * 同步支付回调
     * @param type $ExternalData
     * @param type $paymentId
     * @param type $money
     * @param type $message
     * @param type $orderNo
     */
    public function serverCallback($ExternalData, &$paymentId, &$money, &$message, &$orderNo) {
        
    }

}

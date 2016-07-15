<?php

/**
 * 支付方式 操作类
 *
 * @author baihua <baihua_2011@163.com>
 */
class PayLogic {

    /**
     * 创建支付类实例
     * @param $paymentId int 支付方式ID
     * @return 返回支付插件类对象
     */
    public static function createPaymentInstance($paymentId) {
        $paymentInfo = Payment::getPaymentById($paymentId);
        if ($paymentInfo && isset($paymentInfo['class_name']) && $paymentInfo['class_name']) {
            $className = $paymentInfo['class_name'];
            $classPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . "payments" . DIRECTORY_SEPARATOR . $className . '.php';
            if (file_exists($classPath)) {
                return new $className($paymentId);
            } else {
                Common::showWarning('支付接口类'.$className.'没有找到');
            }
        } else {
            Common::showWarning('支付方式不存在');
        }
    }

    /**
     * @brief 获取订单中的支付信息 M:必要信息; P表示用户;
     * @param $paymentId int    支付方式ID
     * @param $type       string 信息获取方式 order:订单支付;recharge:在线充值;
     * @param $argument   mix    参数
     * @return array 支付提交信息
     */
    public static function getPaymentInfo($paymentId, $type, $argument) {

        //初始化配置参数
        $paymentInstance = PayLogic::createPaymentInstance($paymentId);
        $payment = $paymentInstance->configParam();

        //获取公共信息
        $paymentInfo = Payment::getPaymentById($paymentId, 'config_param');
        if ($paymentInfo) {
            $paymentInfo = CJSON::decode($paymentInfo);
            foreach ($paymentInfo as $key => $item) {
                $payment[$key] = $item;
            }
        }

        if ('order' == $type) {
            //获取订单信息
            $orderObj = new Order();
            $orderInfo = $orderObj->find(array(
                'select' => array('id', 'order_no', 'order_amount', 'postscript', 'mobile', 'accept_name', 'address'),
                'condition' => 'id=:orderId  and status = 1',
                'params' => array(':orderId' => $argument)
            ));
            if (empty($orderInfo)) {
                Common::showWarning('订单信息不正确，不能进行支付');
            }
            //判断商品库存
            $orderGoodsObj = new OrderGoods();
            $orderGoodsList = $orderGoodsObj->findAll(array(
                'select' => array('goods_id', 'product_id', 'goods_nums'),
                'condition' => 'order_id=:orderId',
                'params' => array(':orderId' => $argument)
            ));
            foreach ($orderGoodsList as $key => $val) {
                if (!GoodsLogic::checkStore($val['goods_nums'], $val['goods_id'], $val['product_id'])) {
                    Common::showWarning('商品库存不足无法支付，请重新下单');
                }
            }
            $payment['M_Remark'] = $orderInfo['postscript'];
            $payment['M_OrderId'] = $orderInfo['id'];
            $payment['M_OrderNO'] = $orderInfo['order_no'];
            $payment['M_Amount'] = $orderInfo['order_amount'];

            //用户信息
            $payment['P_Mobile'] = $orderInfo['mobile'];
            $payment['P_Name'] = $orderInfo['accept_name'];
            $payment['P_Address'] = $orderInfo['address'];
        }

        //交易信息
        $payment['M_Time'] = time();
        $payment['M_Paymentid'] = $paymentId;
        return $payment;
    }

}

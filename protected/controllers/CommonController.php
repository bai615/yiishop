<?php

/**
 * 公共模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommonController extends BaseController {

    /**
     * 获取地区
     */
    public function actionAreaChild() {
        $parentId = intval(Yii::app()->request->getParam('aid'));
        $areaModel = new Areas();
        $areaList = $areaModel->findAll(array(
            'select' => array('area_id', 'parent_id', 'area_name', 'sort'),
            'condition' => 'parent_id=:parentId',
            'params' => array(':parentId' => $parentId),
            'order' => 'sort asc'
        ));
        if ($areaList) {
            echo CJSON::encode($areaList);
        }
    }

    /**
     * 成功提示页
     */
    public function actionSuccess() {
        $data['message'] = Yii::app()->request->getParam('message');
        $this->render('success', $data);
    }

    /**
     * 404
     */
    public function actionError() {
        $this->render('404');
    }

    /**
     * 警告提示
     */
    public function actionWarning() {
        $data['message'] = Yii::app()->request->getParam('message');
        $this->render('warning', $data);
    }
    
    /**
     * 进行支付支付方法
     */
    public function actionDoPay() {
        $this->is_login();
        $orderId = intval(Yii::app()->request->getParam('order_id'));
        $paymentId = intval(Yii::app()->request->getParam('payment_id'));
        $recharge = Yii::app()->request->getParam('recharge');
        if ($orderId) {
            //获取订单信息
            $orderObj = new Order();
            $orderInfo = $orderObj->find(array(
                'select' => 'pay_type',
                'condition' => 'id=:orderId',
                'params' => array(':orderId' => $orderId)
            ));
            if (empty($orderInfo)) {
                Common::showWarning('要支付的订单信息不存在');
            }
            $paymentId = $orderInfo['pay_type'];
        }
        //获取支付方式类库
        $paymentInstance = PayLogic::createPaymentInstance($paymentId);
        //在线充值
        if ($recharge) {
            
        }
        //订单支付
        else if ($orderId) {
            $sendData = $paymentInstance->getSendData(PayLogic::getPaymentInfo($paymentId, 'order', $orderId));
        } else {
			Common::showWarning('发生支付错误');
        }
        $paymentInstance->doPay($sendData);
    }


}

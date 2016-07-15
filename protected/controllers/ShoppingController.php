<?php

/**
 * 购物
 *
 * @author baihua <baihua_2011@163.com>
 */
class ShoppingController extends BaseController {

    /**
     * 订单信息核对确认
     */
    public function actionConfirm() {
        $this->is_login();
        $userId = $this->_userI['userId'];
        $id = intval(Yii::app()->request->getParam('id'));
        $buyNum = intval(Yii::app()->request->getParam('num'));
        $type = Yii::app()->request->getParam('type');

        $addressList = Address::getAddress($userId);
        $paymentList = Payment::getPaymentList();

        //计算商品
        $countSumObj = new CountSum();
        $cartInfo = $countSumObj->cartCount($id, $type, $buyNum);

        $data['gid'] = $id;
        $data['type'] = $type;
        $data['addressList'] = $addressList;
        $data['paymentList'] = $paymentList;
        $data['cartInfo'] = $cartInfo;
        $this->render('confirm', $data);
    }

    /**
     * 收货地址弹出框
     */
    public function actionAddress() {
        $userId = $this->_userI['userId'];
        $addressId = intval(Yii::app()->request->getParam('id'));
        $addressRow = array();
        if ($userId && $addressId) {
            $model = new Address();
            $addressRow = $model->find(array(
                'condition' => 'id=:addressId and user_id=:userId',
                'params' => array(':addressId' => $addressId, ':userId' => $userId)
            ));
        }
        $this->renderPartial('address', array('addressRow' => $addressRow));
    }

    /**
     * 添加地址
     */
    public function actionAddressAdd() {
        $addressId = Yii::app()->request->getParam('id');
        $data['accept_name'] = Yii::app()->request->getParam('accept_name');
        $data['province'] = intval(Yii::app()->request->getParam('province'));
        $data['city'] = intval(Yii::app()->request->getParam('city'));
        $data ['area'] = intval(Yii::app()->request->getParam('area'));
        $data['address'] = Yii::app()->request->getParam('address');
        $data['mobile'] = Yii::app()->request->getParam('mobile');
        $userId = $this->_userI['userId'];
        $addressData = array();
        $addressModel = new Address();
        foreach ($data as $key => $value) {
            if (!$value) {
                $result = array('result' => false, 'msg' => '请仔细填写收货地址');
                die(CJSON::encode($result));
            }
            $addressModel->$key = $value;
            $addressData[$key] = $value;
        }
        if ($userId) {
            if ($addressId) {
                $addressModel->updateAll($data, 'id=:addressId and user_id=:userId', array(':addressId' => $addressId, ':userId' => $userId));
                $addressData['id'] = $addressId;
            } else {
                $addressModel->user_id = $userId;
                $addressModel->save();
                $addressData['id'] = $addressModel->id;
            }
            $areaList = Areas::name($data['province'], $data['city'], $data ['area']);
            $addressData['province_val'] = $areaList[$data['province']];
            $addressData['city_val'] = $areaList[$data['city']];
            $addressData['area_val'] = $areaList[$data ['area']];
            $result = array('data' => $addressData);
        } else {
            $result = array('result' => false, 'msg' => '添加失败，请稍后重试');
        }
        die(CJSON::encode($result));
    }

    /**
     * 订单生成
     */
    public function actionOrder() {
        $gid = intval(Yii::app()->request->getParam('direct_gid'));
        $buyNum = intval(Yii::app()->request->getParam('direct_num'));
        $type = Yii::app()->request->getParam('direct_type');
        $addressId = intval(Yii::app()->request->getParam('radio_address'));
        $paymentId = intval(Yii::app()->request->getParam('radio_payment'));
        $message = Yii::app()->request->getParam('message');
        $userId = $this->_userI['userId'];

        //计算商品
        $countSumObj = new CountSum();
        $cartInfo = $countSumObj->cartCount($gid, $type, $buyNum);

        //处理收件地址
        $addressModel = new Address();
        $addressInfo = $addressModel->find(array(
            'condition' => 'id=:addressId and user_id=:userId',
            'params' => array(':addressId' => $addressId, ':userId' => $userId)
        ));
        if (empty($addressInfo)) {
            die('<script>alert("收货地址信息不存在");window.history.go(-1);</script>');
        }

        //检查订单重复
        $checkData = array(
            "accept_name" => $addressInfo['accept_name'],
            "address" => $addressInfo['address'],
            "mobile" => $addressInfo['mobile'],
        );

        //检查订单重复
        $result = OrderLogic::checkRepeat($checkData, $cartInfo['goodsList']);
        if (is_string($result)) {
            die('<script>alert("' . $result . '");window.history.go(-1);</script>');
        }
        //处理支付方式
        $paymentObj = new Payment();
        $paymentInfo = $paymentObj->find(array(
            'select' => array('name', 'type'),
            'condition' => 'id=:paymentId',
            'params' => array(':paymentId' => $paymentId)
        ));
        if (empty($paymentInfo)) {
            die('<script>alert("支付方式错误");window.history.go(-1);</script>');
        }

        //生成的订单数据
        $orderObj = new Order();
        $orderObj->order_no = OrderLogic::createOrderNo();
        $orderObj->user_id = $userId;
        $orderObj->accept_name = $addressInfo['accept_name'];
        $orderObj->pay_type = $paymentId;
        $orderObj->province = $addressInfo['province'];
        $orderObj->city = $addressInfo['city'];
        $orderObj->area = $addressInfo['area'];
        $orderObj->address = $addressInfo['address'];
        $orderObj->mobile = $addressInfo['mobile'];
        $orderObj->create_time = date('Y-m-d H:i:s');
        $orderObj->postscript = $message;
        //商品价格
        $orderObj->payable_amount = $cartInfo['sum'];
        $orderObj->real_amount = $cartInfo['final_sum'];
        //订单应付总额
        $orderObj->order_amount = $cartInfo['final_sum'];
        //备注信息
        $orderObj->note = '';
        $orderObj->save();
        $orderId = $orderObj->id;

        //将订单中的商品插入到order_goods表
        $orderInstance = new OrderLogic();
        $orderInstance->insertOrderGoods($orderId, $cartInfo['goodsList']);

        $data['orderId'] = $orderId;
        $data['orderNo'] = $orderObj->order_no;
        $data['orderAmount'] = sprintf('%.2f', $orderObj->order_amount);
        $data['paymentInfo'] = $paymentInfo;
        $this->render('order', $data);
    }

    /**
     * 进行支付支付方法
     */
    public function actionDoPay() {
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

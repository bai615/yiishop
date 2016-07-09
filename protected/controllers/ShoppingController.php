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
//        $result = OrderLogic::checkRepeat($checkData, $cartInfo['goodsList']);
//        if (is_string($result)) {
//            IError::show(403, $result);
//        }
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

        $data = array();
        $data['orderId'] = $orderId;
        $data['orderNo'] = $orderObj->order_no;
        $data['orderAmount'] = sprintf('%.2f',$orderObj->order_amount);
        $data['paymentName'] = $paymentInfo['name'];
        $this->render('order', $data);
    }
    
    public function actionDoPay(){
        echo '去支付';
    }

}

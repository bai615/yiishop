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
        $addressId = intval(Yii::app()->request->getParam('radio_address'));
        pprint($_POST);
    }

}

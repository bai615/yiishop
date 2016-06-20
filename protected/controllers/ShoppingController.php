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
        $goodsId = Yii::app()->request->getParam('id');
        $buyNum = Yii::app()->request->getParam('num');
        $this->render('confirm');
    }

    public function actionAddress() {
        $userId = 1; //$this->user['user_id'];
        $id = Yii::app()->request->getParam('id');
        if ($userId) {
            if ($id) {
//                $addressDB = new IModel('address');
//                $this->addressRow = $addressDB->getObj('user_id = ' . $user_id . ' and id = ' . $id);
            }
        } else {
//            $this->addressRow = ISafe::get('address');
        }
        $this->renderPartial('address');
    }

    public function actionAddressAdd() {
        
    }

}

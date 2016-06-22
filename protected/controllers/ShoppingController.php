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
        $goodsId = intval(Yii::app()->request->getParam('id'));
        $buyNum = intval(Yii::app()->request->getParam('num'));
        $this->render('confirm');
    }

    /**
     * 收货地址弹出框
     */
    public function actionAddress() {
        $userId = 1; //$this->user['user_id'];
        $id = intval(Yii::app()->request->getParam('id'));
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
        pprint($_POST);
//        $accept_name = IFilter::act(IReq::get('accept_name'));
//		$province    = IFilter::act(IReq::get('province'),'int');
//		$city        = IFilter::act(IReq::get('city'),'int');
//		$area        = IFilter::act(IReq::get('area'),'int');
//		$address     = IFilter::act(IReq::get('address'));
//		$zip         = IFilter::act(IReq::get('zip'));
//		$telphone    = IFilter::act(IReq::get('telphone'));
//		$mobile      = IFilter::act(IReq::get('mobile'));
//        $user_id     = $this->user['user_id'];
    }

}

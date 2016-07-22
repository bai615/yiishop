<?php

/**
 * 用户中心模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class UcenterController extends BaseController {

    public $currentMenu = 0;
    public $menuData = array(
        '1' => array(
            'name' => '我的订单',
            'url' => '/ucenter/order'
        ),
        '2' => array(
            'name' => '账户余额',
            'url' => '/ucenter/account'
        ),
        '3' => array(
            'name' => '我的收藏',
            'url' => '/ucenter/order'
        ),
        '4' => array(
            'name' => '地址管理',
            'url' => '/ucenter/order'
        ),
        '5' => array(
            'name' => '个人资料',
            'url' => '/ucenter/order'
        ),
        '6' => array(
            'name' => '修改密码',
            'url' => '/ucenter/order'
        ),
    );

    /**
     * 收货地址删除处理
     */
    public function actionAddressDel() {
        $userId = $this->_userI['userId'];
        $addressId = intval(Yii::app()->request->getParam('id'));
        $model = new Address();
        $info = $model->find(array(
            'condition' => 'id=:addressId and user_id=:userId',
            'params' => array(':addressId' => $addressId, ':userId' => $userId)
        ));
        $result = array('result' => false, 'msg' => '删除失败，请稍后重试');
        if ($info) {
            $flag = $info->delete();
            if ($flag) {
                $result = array('result' => true, 'msg' => '删除成功');
            }
        }
        die(CJSON::encode($result));
    }

    /**
     * 余额付款
     */
    public function actionPaymentBalance() {
        $userId = $this->_userI['userId'];

        $return['attach'] = intval(Yii::app()->request->getParam('attach'));
        $return['total_fee'] = sprintf('%.2f', floatval(Yii::app()->request->getParam('total_fee')));
        $return['order_no'] = Yii::app()->request->getParam('order_no');
        $return['return_url'] = Yii::app()->request->getParam('return_url');
        $oldSign = Yii::app()->request->getParam('sign');

        if ($return['total_fee'] < 0 || $return['order_no'] == '' || $return['return_url'] == '') {
            Common::showWarning('支付参数不正确');
        }

        $paymentObj = new Payment();
        $paymentInfo = $paymentObj->find(array(
            'select' => 'id',
            'condition' => 'class_name = "BalancePay" '
        ));

        $partnerKey = Payment::getConfigParam($paymentInfo['id'], 'M_PartnerKey');
        $newSign = Common::getSign($return, $userId . $partnerKey);
        if ($oldSign != $newSign) {
            Common::showWarning('数据校验不正确');
        }
        //获取用户信息
        $memberObj = new Member();
        $memberInfo = $memberObj->find(array(
            'condition' => 'user_id=:userId',
            'params' => array(':userId' => $userId)
        ));
        if (empty($memberInfo)) {
            Common::showWarning('用户信息不存在');
        }
        //获取订单信息
        $orderObj = new Order();
        $orderInfo = $orderObj->find(array(
            'condition' => 'order_no=:orderNo and pay_status = 0 and status = 1 and user_id=:userId',
            'params' => array(':orderNo' => $return['order_no'], ':userId' => $userId)
        ));
        if (empty($orderInfo)) {
            Common::showWarning('订单号【' . $return['order_no'] . '】已经被处理过，请查看订单状态');
        }
        if ($memberInfo['balance'] < $orderInfo['order_amount']) {
            Common::showWarning('账户余额不足，请到用户中心充值');
        }

        //扣除余额并且记录日志
        $logObj = new AccountLog();
        $config = array(
            'user_id' => $userId,
            'event' => 'pay',
            'amount' => $return['total_fee'],
            'order_no' => $return['order_no'],
        );
        $is_success = $logObj->write($config);
        if (true !== $is_success) {
            Common::showWarning('用户余额更新失败');
        }

        $orderId = OrderLogic::updateOrderStatus($return['order_no']);
        if (empty($orderId)) {
            Common::showWarning('订单号【' . $return['order_no'] . '】更新失败');
        }
        $return['is_success'] = $is_success ? 'T' : 'F';
        ksort($return);

        //返还的URL地址
        $responseUrl = '';
        foreach ($return as $key => $val) {
            $responseUrl .= $key . '=' . urlencode($val) . '&';
        }
        $nextUrl = urldecode($return['return_url']);
        if (stripos($nextUrl, '?') === false) {
            $return_url = $nextUrl . '?' . $responseUrl;
        } else {
            $return_url = $nextUrl . '&' . $responseUrl;
        }
        $responseUrl = substr($responseUrl, 0, -1);
        //计算要发送的md5校验
        $urlStrMD5 = md5($responseUrl . $userId . $partnerKey);

        //拼接进返还的URL中
        $return_url.= 'sign=' . $urlStrMD5;
        header('location:' . $return_url);
    }

    /**
     * 我的订单
     */
    public function actionOrder() {
        $this->currentMenu = 1;
        $userId = $this->_userI['userId'];
        $orderModel = new Order();
        $condition = 'user_id =:userId and if_del= 0';
        $params = array(':userId' => $userId);
        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = 'id desc';
        $criteria->with = 'r_ordergoods';
        $count = $orderModel->count($condition, $params);
        $page = new CPagination($count);
        $page->pageSize = 10;
        $page->applyLimit($criteria);
        $orderList = $orderModel->findAll($criteria);
        $this->render('order', array('pages' => $page, 'count' => $count, 'orderList' => $orderList));
    }

    /**
     * 订单详情
     */
    public function actionOrderDetail() {
        $this->currentMenu = 1;
        $userId = $this->_userI['userId'];
        $orderId = Yii::app()->request->getParam('id');
        //订单信息
        $orderModel = new Order();
        $orderInfo = $orderModel->find(array(
            'condition' => 'o.id=:orderId and user_id=:userId',
            'params' => array(':orderId' => $orderId, ':userId' => $userId),
            'alias' => 'o',
            'with' => 'r_ordergoods'
        ));
        //地址信息
        $areaData = Areas::name($orderInfo['province'], $orderInfo['city'], $orderInfo['area']);
        $this->render('orderDetail', array('orderInfo' => $orderInfo, 'areaData' => $areaData));
    }

    /**
     * 账户余额
     */
    public function actionAccount() {
        $this->currentMenu = 2;
        $userId = $this->_userI['userId'];
        $memberModel = new Member();
        $memberInfo = $memberModel->find(array(
            'select' => array('user_id', 'balance'),
            'condition' => 'user_id=:userId',
            'params' => array(':userId' => $userId)
        ));
        $logModel = new AccountLog();
        $condition = 'user_id =:userId';
        $params = array(':userId' => $userId);
        $criteria = new CDbCriteria();
        $criteria->select = 'amount, amount_log, time, note';
        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = 'id desc';
        $count = $logModel->count($condition, $params);
        $page = new CPagination($count);
        $page->pageSize = 10;
        $page->applyLimit($criteria);
        $logList = $logModel->findAll($criteria);
        $this->render('account', array('pages' => $page, 'count' => $count, 'logList' => $logList, 'memberInfo' => $memberInfo));
    }

}

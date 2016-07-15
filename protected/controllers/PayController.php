<?php

/**
 * 支付
 *
 * @author baihua <baihua_2011@163.com>
 */
class PayController extends CController {

    public $data = array();
    public $_userI = array();

    public function init() {
        parent::init();
        $this->_userI = Yii::app()->session['shopUserInfo'];

        $host = Yii::app()->request->getHostInfo();
        $this->data['static_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static';
        $this->data['libs_url'] = $host . '/libs';
        $this->data['css_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/css';
        $this->data['js_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/js';
        $this->data['images_url'] = $host . Yii::app()->theme->getBaseUrl() . '/static/images';
    }

    /**
     * 同步信息返回
     */
    public function actionCallback() {
        //从URL中获取支付方式
        $paymentId = intval(Yii::app()->request->getParam('_id'));
        $paymentInstance = PayLogic::createPaymentInstance($paymentId);
        if (!is_object($paymentInstance)) {
            Common::showWarning('支付方式不存在');
        }
        //初始化参数
        $money = '';
        $message = '支付失败';
        $orderNo = '';
        //执行接口回调函数
        $callbackData = array_merge($_POST, $_GET);
        unset($callbackData['_id']);
        $return = $paymentInstance->callback($callbackData, $paymentId, $money, $message, $orderNo);

        if (true == $return) {
            //支付成功
            $this->redirect($this->createAbsoluteUrl('common/success', array('message' => '支付成功')));
        } else {
            //支付失败
            $message = $message ? $message : '支付失败';
            Common::showWarning($message);
        }
    }

    /**
     * 异步信息通知
     */
    public function actionNotify() {
        
    }

}

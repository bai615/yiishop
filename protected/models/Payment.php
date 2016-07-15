<?php

/**
 * 支付方式表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Payment extends CActiveRecord {

    /**
     * 根据支付方式配置编号  获取该插件的详细配置信息
     * @param $paymentId int    支付方式ID
     * @param $key        string 字段
     * @return 返回支付插件类对象
     */
    public static function getPaymentById($paymentId, $key = '') {
        $info = self::model()->findByPk($paymentId);
        if ($key) {
            return isset($info[$key]) ? $info[$key] : '';
        }
        return $info;
    }

    /**
     * 根据支付方式配置编号  获取该插件的配置信息
     * @param $payment_id int    支付方式ID
     * @param $key        string 字段
     * @return 返回支付插件类对象
     */
    public static function getConfigParam($payment_id, $key = '') {
        $payConfig = self::getPaymentById($payment_id, 'config_param');
        if ($payConfig) {
            $payConfig = CJSON::decode($payConfig);
            return isset($payConfig[$key]) ? $payConfig[$key] : '';
        }
        return '';
    }

    /**
     * 获取支付方式
     * @return type
     */
    public static function getPaymentList() {
        return self::model()->findAll(array(
                'condition' => 'status = 0',
                'order' => '`order` asc'
        ));
    }

    /**
     * model 的静态方法
     * @param type $className
     * @return type
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 对应表名
     * @return string
     */
    public function tableName() {
        return '{{payment}}';
    }

}

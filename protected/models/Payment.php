<?php

/**
 * 支付方式表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Payment extends CActiveRecord {

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

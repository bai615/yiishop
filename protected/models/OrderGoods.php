<?php

/**
 * 订单商品表
 *
 * @author baihua <baihua_2011@163.com>
 */
class OrderGoods extends CActiveRecord {

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
        return '{{order_goods}}';
    }

}

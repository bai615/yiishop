<?php

/**
 * 订单商品表
 *
 * @author baihua <baihua_2011@163.com>
 */
class OrderGoods extends CActiveRecord {
    
    /**
     * 关联关系
     * @return type
     */

    public function relations() {
        return array(
            'r_order' => array(self::BELONGS_TO, 'Order', 'order_id'),
        );
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
        return '{{order_goods}}';
    }

}

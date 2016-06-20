<?php

/**
 * 推荐类商品
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommendGoods extends CActiveRecord {

    public $img; //商品图片
    public $name; //商品名称
    public $sell_price; //商品销售价格
    public $market_price; //商品市场价格

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
        return '{{commend_goods}}';
    }

}

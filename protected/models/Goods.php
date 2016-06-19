<?php

/**
 * 商品信息表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Goods extends CActiveRecord {

    public $brand_name; //商品品牌名称
    public $category_id; //分类ID
    public $photo; //商品图片集
    public $price_area; //价格区间

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
        return '{{goods}}';
    }

}

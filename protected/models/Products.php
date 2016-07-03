<?php

/**
 * 货品表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Products extends CActiveRecord {

    public $maxSellPrice; //最高售价
    public $minSellPrice; //最低售价
    public $maxMarketPrice; //最高市场价
    public $minMarketPrice; //最低市场价

    /**
     * 关联关系
     * @return type
     */

    public function relations() {
        return array(
            'r_goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
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
        return '{{products}}';
    }

}

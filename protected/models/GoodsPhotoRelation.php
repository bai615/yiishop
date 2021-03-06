<?php

/**
 * 相册商品关系表
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsPhotoRelation extends CActiveRecord {
    
    public $img;//图片路径

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
        return '{{goods_photo_relation}}';
    }

}
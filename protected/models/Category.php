<?php

/**
 * 产品分类表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Category extends CActiveRecord {

    /**
     * 关联关系
     * @return type
     */
    public function relations() {
        return array(
            'r_categoryExtend' => array(self::HAS_MANY, 'CategoryExtend', 'category_id'),
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
        return '{{category}}';
    }

}

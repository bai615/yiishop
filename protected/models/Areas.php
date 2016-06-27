<?php

/**
 * 地区信息
 *
 * @author baihua <baihua_2011@163.com>
 */
class Areas extends CActiveRecord {

    /**
     * 根据传入的地域ID获取地域名称，获取的名称是根据ID依次获取的
     * @param int 地域ID 匿名参数可以多个id
     * @return array
     */
    public static function name() {
        $result = array();
        $paramArray = func_get_args();
        $areaDB = new Areas();
        $areaData = $areaDB->findAll("area_id in (" . trim(join(',', $paramArray), ",") . ")");

        foreach ($areaData as $key => $value) {
            $result[$value['area_id']] = $value['area_name'];
        }
        return $result;
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
        return '{{areas}}';
    }

}

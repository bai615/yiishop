<?php

/**
 * 收货信息表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Address extends CActiveRecord {
    
    public $province_val;
    public $city_val;
    public $area_val;

    /**
     * 获取收货地址
     * @param type $userId
     * @return type
     */
    public static function getAddress($userId) {
        $addressObj = new Address();
        $addressList = $addressObj->findAll(array(
            'condition' => 'user_id=:userId',
            'params' => array(':userId' => $userId),
            'order' => 'is_default desc'
        ));
        //更新$addressList数据
        foreach ($addressList as $key => $val) {
            $temp = Areas::name($val['province'], $val['city'], $val['area']);
            if (isset($temp[$val['province']]) && isset($temp[$val['city']]) && isset($temp[$val['area']])) {
                $addressList[$key]['province_val'] = $temp[$val['province']];
                $addressList[$key]['city_val'] = $temp[$val['city']];
                $addressList[$key]['area_val'] = $temp[$val['area']];
            }
        }
        return $addressList;
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
        return '{{address}}';
    }

}

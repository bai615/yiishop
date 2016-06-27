<?php

/**
 * 用户表
 *
 * @author baihua <baihua_2011@163.com>
 */
class User extends CActiveRecord {

    /**
     * 通过名字获取用户信息
     * @param type $userName
     * @return type
     */
    public function getUserByName($userName) {
        return $this->find(array(
                'select' => array('id', 'username', 'password', 'head_ico', 'salt'),
                'condition' => 'username=:userName',
                'params' => array(':userName' => $userName)
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
        return '{{user}}';
    }

}

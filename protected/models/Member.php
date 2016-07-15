<?php

/**
 * 用户信息表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Member extends CActiveRecord {

    /**
     * 更新账号金额
     * @param type $finnalAmount
     * @param type $userId
     * @return type
     */
    public function updateBalance($finnalAmount, $userId) {
        $memberModel = new Member();
        $memberInfo = $memberModel->find(array(
            'select' => array('user_id', 'balance'),
            'condition' => 'user_id=:userId',
            'params' => array(':userId' => $userId)
        ));
        $memberInfo->balance = $finnalAmount;
        return $memberInfo->update();
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
        return '{{member}}';
    }

}

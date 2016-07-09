<?php

/**
 * 用户中心模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class UcenterController extends BaseController {

    /**
     * 收货地址删除处理
     */
    public function actionAddressDel() {
        $userId = $this->_userI['userId'];
        $addressId = intval(Yii::app()->request->getParam('id'));
        $model = new Address();
        $info = $model->find(array(
            'condition' => 'id=:addressId and user_id=:userId',
            'params' => array(':addressId' => $addressId, ':userId' => $userId)
        ));
        $result = array('result' => false, 'msg' => '删除失败，请稍后重试');
        if ($info) {
            $flag = $info->delete();
            if ($flag) {
                $result = array('result' => true, 'msg' => '删除成功');
            }
        }
        die(CJSON::encode($result));
    }
    
    public function actionOrder(){
        echo '订单中心';
    }

}

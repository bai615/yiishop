<?php

/**
 * 公共模块
 *
 * @author baihua <baihua_2011@163.com>
 */
class CommonController extends BaseController {

    /**
     * 获取地区
     */
    public function actionAreaChild() {
        $parentId = intval(Yii::app()->request->getParam('aid'));
        $areaModel = new Areas();
        $areaList = $areaModel->findAll(array(
            'select' => array('area_id', 'parent_id', 'area_name', 'sort'),
            'condition' => 'parent_id=:parentId',
            'params' => array(':parentId' => $parentId),
            'order' => 'sort asc'
        ));
        if ($areaList) {
            echo CJSON::encode($areaList);
        }
    }

    /**
     * 成功提示页
     */
    public function actionSuccess() {
        $data['message'] = Yii::app()->request->getParam('message');
        $this->render('success', $data);
    }

    /**
     * 404
     */
    public function actionError() {
        $this->render('404');
    }

    /**
     * 警告提示
     */
    public function actionWarning() {
        $data['message'] = Yii::app()->request->getParam('message');
        $this->render('warning', $data);
    }

}

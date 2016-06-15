<?php

/**
 * 首页
 *
 * @author baihua <baihua_2011@163.com>
 */
class HomeController extends BaseController {

    public function actionIndex() {
//        pprint(Yii::getFrameworkPath());
//        N:\phpStudy\WWW\testshop\yiishop\yii
        //http://sunas.cn/   健康商城
        $this->render('index');
    }

    public function actionProducts() {
        $goodsId = Yii::app()->request->getParam('id');

        //使用商品id获得商品信息
        $goodsModel = new Goods();
        $goodsInfo = $goodsModel->find(array(
            'condition' => 'id=:goodsId and is_del=0',
            'params' => array(':goodsId' => $goodsId)
        ));
        //品牌名称
        if ($goodsInfo['brand_id']) {
            $brandModel = new IModel('brand');
            $brandInfo = $brandModel->find(array(
                'select' => 'name',
                'condition' => 'id=:brindId',
                'params' => array('brandId' => $goodsInfo['brand_id'])
            ));
            if ($brandInfo) {
                $goodsInfo['brand_name'] = $brandInfo['name'];
            }
        }
        pprint($goodsInfo);
        $this->render('products');
    }

}

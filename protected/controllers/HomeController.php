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
        //商品图片
        $goodsPhotoModel = new GoodsPhoto();
        $goodsPhotoList = $goodsPhotoModel->findAll(array(
            'select' => array('p.id', 'p.img'),
            'condition' => 'g.goods_id=:goodsId',
            'params' => array(':goodsId' => $goodsId)
        ));
        $tb_goods_photo = new IQuery('goods_photo_relation as g');
        $tb_goods_photo->fields = 'p.id AS photo_id,p.img ';
        $tb_goods_photo->join = 'left join goods_photo as p on p.id=g.photo_id ';
        $tb_goods_photo->where = ' g.goods_id=' . $goods_id;
        $goods_info['photo'] = $tb_goods_photo->find();
        foreach ($goods_info['photo'] as $key => $val) {
            //对默认第一张图片位置进行前置
            if ($val['img'] == $goods_info['img']) {
                $temp = $goods_info['photo'][0];
                $goods_info['photo'][0] = $val;
                $goods_info['photo'][$key] = $temp;
            }
        }
        //pprint($goodsInfo);
        $this->render('products');
    }

}

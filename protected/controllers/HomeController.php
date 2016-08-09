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
        //获取商品分类
        $categoryModel = new CategoryExtend();
        $categoryInfo = $categoryModel->find(array(
            'select' => array('c.id'),
            'condition' => 'ca.goods_id = :goodsId',
            'params' => array(':goodsId' => $goodsId),
            'join' => 'left join {{category}} c on c.id=ca.category_id',
            'alias' => 'ca',
            'order' => 'ca.id desc'
        ));


        /**
         * 获取商品分类，第2方案
          $condition = 'ca.goods_id = :goodsId';
          $params[':goodsId'] = $goodsId;
          $criteria = new CDbCriteria;
          $criteria->condition = $condition;
          $criteria->params = $params;
          $criteria->order = 'ca.id desc';
          $criteria->with = 'r_category';
          $criteria->alias = 'ca';
          $categoryInfo = $categoryModel->find($criteria);
         */
        $goodsInfo['category_id'] = empty($categoryInfo) ? 0 : $categoryInfo['id'];

        //商品图片
        $goodsPhotoModel = new GoodsPhotoRelation();
        $goodsPhotoList = $goodsPhotoModel->findAll(array(
            'select' => array('p.id as photo_id', 'p.img'),
            'condition' => 'g.goods_id=:goodsId',
            'params' => array(':goodsId' => $goodsId),
            'alias' => 'g',
            'join' => 'left join {{goods_photo}} p on p.id=g.photo_id'
        ));
        if ($goodsPhotoList) {
            $goodsPhotoArr = array();
            foreach ($goodsPhotoList as $key => $value) {
                $goodsPhotoArr[$key]['img'] = $value['img'];
                $goodsPhotoArr[$key]['photo_id'] = $value['photo_id'];
                //对默认第一张图片位置进行前置
                if ($value['img'] == $goodsInfo['img']) {
                    $temp = $goodsPhotoArr[0];
                    $goodsPhotoArr[0]['img'] = $value['img'];
                    $goodsPhotoArr[0]['photo_id'] = $value['photo_id'];
                    $goodsPhotoArr[$key] = $temp;
                }
            }
            $goodsInfo['photo'] = $goodsPhotoArr;
        }



        //获得商品的价格区间
        $productModel = new Products();
        $productList = $productModel->find(array(
            'select' => array('max(sell_price) as maxSellPrice', 'min(sell_price) as minSellPrice', 'max(market_price) as maxMarketPrice', 'min(market_price) as minMarketPrice'),
            'condition' => 'goods_id=:goodsId',
            'params' => array(':goodsId' => $goodsId)
        ));
        if ($productList) {
            $priceArea['maxSellPrice'] = $productList['maxSellPrice'];
            $priceArea['minSellPrice'] = $productList['minSellPrice'];
            $priceArea['minMarketPrice'] = $productList['minMarketPrice'];
            $priceArea['maxMarketPrice'] = $productList['maxMarketPrice'];
            $goodsInfo['price_area'] = $priceArea;
        }

//        dprint($goodsInfo);
        $this->render('products', array('goodsInfo' => $goodsInfo));
    }

    /**
     * 获取货品数据
     */
    public function actionGetProduct() {
        $specJSON = Yii::app()->request->getParam('specJSON');
        $jsonData = CJSON::decode($specJSON);
        if (empty($jsonData)) {
            die(CJSON::encode(array('flag' => 'fail', 'message' => '规格值不符合标准')));
        }
        $goodsId = intval(Yii::app()->request->getParam('goods_id'));
        //获取货品数据
        $productsObj = new Products();
        $procductsInfo = $productsObj->find(array(
            'condition' => 'goods_id=:goodsId and spec_array=:specJSON',
            'params' => array(':goodsId' => $goodsId, ':specJSON' => $specJSON)
        ));
        //匹配到货品数据
        if (empty($procductsInfo)) {
            die(CJSON::encode(array('flag' => 'fail', 'message' => '没有找到相关货品')));
        } else {
            $data = array(
                'id' => $procductsInfo['id'],
                'goods_id' => $procductsInfo['goods_id'],
                'products_no' => $procductsInfo['products_no'],
                'spec_array' => $procductsInfo['spec_array'],
                'store_nums' => $procductsInfo['store_nums'],
                'market_price' => $procductsInfo['market_price'],
                'sell_price' => $procductsInfo['sell_price'],
                'cost_price' => $procductsInfo['cost_price'],
                'weight' => $procductsInfo['weight']
            );
            die(CJSON::encode(array('flag' => 'success', 'info' => $data)));
        }
    }

    /**
     * 添加到收藏
     */
    public function actionFavoriteAdd() {
        $userId = $this->_userI['userId'];
        $goodsId = Yii::app()->request->getParam('goods_id');
        if (empty($goodsId)) {
            $errCode = 1;
            $message = '商品id值不能为空';
        } else if (empty($this->_userI)) {
            $errCode = 2;
            $message = '请先登录';
        } else {
            $favoriteModel = new Favorite();
            $favoriteInfo = $favoriteModel->find(array(
                'condition' => 'user_id=:userId and rid=:goodsId',
                'params' => array(':userId' => $userId, ':goodsId' => $goodsId)
            ));
            if ($favoriteInfo) {
                $errCode = 3;
                $message = '您已经收藏过此件商品';
            } else {
                $favoriteModel->user_id = $userId;
                $favoriteModel->rid = $goodsId;
                $favoriteModel->time = date('Y-m-d H:i:s');
                $favoriteModel->save();
                $errCode = 0;
                $message = '收藏成功';
            }
        }
        echo (json_encode(array('errCode' => $errCode, 'errMsg' => $message)));
    }

}

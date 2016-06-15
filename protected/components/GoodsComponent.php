<?php

/**
 * goods 组件
 *
 * @author baihua <baihua_2011@163.com>
 */
class GoodsComponent extends CApplicationComponent {

    /**
     * 所有一级分类
     * @return type
     */
    public function getCategoryListTop() {
        $categoryModel = new Category();
        $categoryList = $categoryModel->findAll(array(
            'select' => array('id', 'name'),
            'condition' => 'parent_id = 0 and visibility = 1',
            'order' => 'sort asc',
            'limit' => 20
        ));
        return $categoryList;
        /*
          'name'  => 'category',
          'where' => ' parent_id = 0 and visibility = 1 ',
          'order' => ' sort asc',
          'limit' => 20,
         */
    }

    /**
     * 根据一级分类输出二级分类列表
     * @param type $parentId
     * @return type
     */
    public function getCategoryByParentid($parentId) {
        $categoryModel = new Category();
        $categoryList = $categoryModel->findAll(array(
            'select' => array('id', 'name'),
            'condition' => 'parent_id = :parentId and visibility = 1',
            'params' => array(':parentId' => $parentId),
            'order' => 'sort asc',
            'limit' => 10
        ));
        return $categoryList;
        /*
          'name'  => 'category',
          'where' => ' parent_id = #parent_id# and visibility = 1 ',
          'order' => ' sort asc',
          'limit' => 10,
         */
    }

    /**
     * 根据分类取销量排名列表
     * @param type $categroyId
     * @param type $limit
     * @return type
     */
    public function getCategoryExtendList($categroyId, $limit = 20) {
        //方案一：
//        $sql = 'select distinct go.id,go.name,go.img,go.sell_price from {{goods}} as go left join {{category_extend}} as ca on ca.goods_id = go.id where ca.category_id in (' . $categroyId . ') and go.is_del = 0 order by sale desc limit ' . $limit;
//        $goodsInfo = Yii::app()->db->createCommand($sql)->queryAll();
        
        //方案二：
// 		$command = Yii::app()->db->createCommand($sql);
// 		$goodsInfo = $command->queryAll();

        //方案三：
        $goodsModel = new Goods();
        $goodsInfo = $goodsModel->findAll(array(
            'select' => array('go.id', 'go.name', 'go.img', 'go.sell_price', 'go.market_price'),
            'condition' => 'ca.category_id in (' . $categroyId . ') and go.is_del = 0',
            'alias' => 'go',
            'join' => 'left join {{category_extend}} as ca on ca.goods_id = go.id',
            'order' => 'sale desc',
            'limit' => $limit,
            'distinct'=>true
        ));
        return $goodsInfo;
        /*
          'name'  => 'goods as go',
          'join'  => 'left join category_extend as ca on ca.goods_id = go.id',
          'where' => 'ca.category_id in (#categroy_id#) and go.is_del = 0',
          'fields'=> 'distinct go.id,go.name,go.img,go.sell_price',
          'order' => 'sale desc',
          'limit' => 10,
         */
    }

}

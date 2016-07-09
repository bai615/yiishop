<?php

/**
 * 订单逻辑
 *
 * @author baihua <baihua_2011@163.com>
 */
class OrderLogic {

    /**
     * 产生订单编号
     * @return string 订单ID
     */
    public static function createOrderNo() {
        return date('YmdHis') . rand(100000, 999999);
    }

    /**
     * 把订单商品同步到order_goods表中
     * @param $orderId 订单ID
     * @param $goodsInfo 商品和货品信息（购物车数据结构,countSum 最终生成的格式）
     */
    public function insertOrderGoods($orderId, $goodsInfo) {
        $orderGoodsObj = new OrderGoods();
        //清理旧的关联数据
        $orderGoodsObj->deleteAllByAttributes(array('order_id' => $orderId));
        if ($goodsInfo) {
            foreach ($goodsInfo as $value) {
                //拼接商品名称和规格数据
                $specArray = array('name' => $value['name'], 'goodsno' => $value['goods_no'], 'value' => '');

                if (isset($value['spec_array'])) {
                    $spec = Common::show_spec($value['spec_array']);
                    foreach ($spec as $skey => $svalue) {
                        $specArray['value'] .= $skey . ':' . $svalue . ',';
                    }
                    $specArray['value'] = trim($specArray['value'], ',');
                }
                $orderGoodsObj = new OrderGoods();
                $orderGoodsObj->order_id = $orderId;
                $orderGoodsObj->product_id = $value['product_id'];
                $orderGoodsObj->goods_id = $value['goods_id'];
                $orderGoodsObj->img = $value['img'];
                $orderGoodsObj->goods_price = $value['sell_price'];
                $orderGoodsObj->real_price = $value['sell_price'];
                $orderGoodsObj->goods_nums = $value['count'];
                $orderGoodsObj->goods_weight = $value['weight'];
                $orderGoodsObj->goods_array = CJSON::encode($specArray);
                $orderGoodsObj->save();
            }
        }
    }

    /**
     * 检查订单是否重复
     * @param array $checkData 检查的订单数据
     * @param array $goodsList 购买的商品数据信息
     */
    public static function checkRepeat($checkData, $goodsList) {
        $checkWhere = array();
        foreach ($checkData as $key => $val) {
            if (empty($val)) {
                return "请仔细填写订单所需内容";
            }
            $checkWhere[] = "`" . $key . "` = '" . $val . "'";
        }
        $checkWhere[] = " NOW() < date_add(create_time,INTERVAL 2 MINUTE) "; //在有限时间段内生成的订单
        $checkWhere[] = " pay_status != 1 "; //是否付款
        $where = join(" and ", $checkWhere);

        //查询订单数据库
        $orderObj = new Order();
        $orderList = $orderObj->find($where);
    }

}

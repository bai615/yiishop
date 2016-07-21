<div class="container block_box">
    <div class="breadcrumb"><span>您当前的位置：</span> <a href="/">首页</a> 》交易订单</div>
    <div class="ucenter_box">
        <div class="col-md-2 ucenter_menu">
            <div class="list-group">
                <a href="#" class="list-group-item active">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <!--<a href="#" class="divider"></a>-->
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
            </div>
        </div>
        <div class="col-md-10 ucenter_main">
            <h4>我的订单</h4>
            <?php
            if ($orderList):
                foreach ($orderList as $orderInfo):
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">订单号：<?php echo $orderInfo['order_no']; ?><span class="order_status"><?php echo Order::orderStatusText(Order::getOrderStatus($orderInfo)); ?></span><span class="order_amount">总额：￥<?php echo $orderInfo['order_amount'];?></span><span class="order_time"><?php echo $orderInfo['create_time']; ?></span></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tbody>
                                    <?php
                                    if ($orderInfo['r_ordergoods']):
                                        foreach ($orderInfo['r_ordergoods'] as $orderGoodsInfo):
                                            ?>
                                            <tr>
                                                <td class="goods_img">
                                                    <a title="<?php echo $orderGoodsInfo['goods_name'];  ?>" target="_bank" href="<?php echo $this->createAbsoluteUrl('home/products', array('id' => $orderGoodsInfo['goods_id'])); ?>">
                                                        <img class="goods_img" src="/<?php echo $orderGoodsInfo['img']; ?>" width="66px" height="66px" alt="<?php echo $orderGoodsInfo['goods_name'];  ?>" title="<?php echo $orderGoodsInfo['goods_name'];  ?>" />
                                                    </a>
                                                </td>
                                                <td class="goods_name">
                                                    <a title="<?php echo $orderGoodsInfo['goods_name'];  ?>" target="_bank" href="<?php echo $this->createAbsoluteUrl('home/products', array('id' => $orderGoodsInfo['goods_id'])); ?>">
                                                        <?php echo $orderGoodsInfo['goods_name']; ?>
                                                    </a>
                                                    <?php if (!empty($orderGoodsInfo['spec_array'])) : ?>
                                                        <p>
                                                            <?php $spec_array = Common::show_spec($orderGoodsInfo['spec_array']); ?>
                                                            <?php foreach ($spec_array as $specName => $specValue) : ?>
                                                                <?php echo isset($specName) ? $specName : ""; ?>：<?php echo isset($specValue) ? $specValue : ""; ?> &nbsp&nbsp
                                                            <?php endforeach; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $orderGoodsInfo['goods_nums']; ?></td>
                                                <td>￥<?php echo $orderGoodsInfo['real_price']; ?></td>
                                                <td>
                                                    <a href="<?php echo $this->createAbsoluteUrl('ucenter/orderDetail', array('id' => $orderInfo['id'])); ?>">订单详情</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>
            <?php
            $this->widget('CLinkPager', array(
                'header' => '',
                'firstPageLabel' => '<<',
                'lastPageLabel' => '>>',
                'prevPageLabel' => '<',
                'nextPageLabel' => '>',
                'pages' => $pages,
                'maxButtonCount' => 5,
            ));
            ?>
        </div>

    </div>
</div>
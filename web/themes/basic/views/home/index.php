<div class="container">
    <?php
    foreach (Yii::app()->goods->getCategoryListTop() as $key => $firstCat) :
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $firstCat['name']; ?></h3>
                <ul>
                    <?php
                    foreach (Yii::app()->goods->getCategoryByParentid($firstCat['id']) as $key => $secondCat) :
                        ?>
                        <li><?php echo $secondCat['name']; ?></li>
                        <?php
                    endforeach;
                    ?>
                </ul>
            </div>
            <div class="panel-body">
                <?php
                foreach (Yii::app()->goods->getCategoryExtendList($firstCat['id'], 8) as $info) :
                    ?>
                    <div class="goods_block">
                        <p class="goods_img">
                            <a title="<?php echo $info['name']; ?>" href="<?php echo $this->createAbsoluteUrl('home/products', array('id' => $info['id'])); ?>">
                                <img class="goods_img" src="<?php echo $info['img']; ?>" alt="<?php echo $info['name']; ?>" title="<?php echo $info['name']; ?>"/>
                            </a>
                        </p>
                        <p class="goods_title"><a title="<?php echo $info['name']; ?>" href="<?php echo $this->createAbsoluteUrl('home/products', array('id' => $info['id'])); ?>"><?php echo $info['name']; ?></a></p>
                        <p class="goods_price">惊喜价：<b>￥<?php echo $info['sell_price']; ?></b></p>
                        <p class="goods_market_price">市场价：<s>￥<?php echo $info['market_price']; ?></s></p>
                        <p class="goods_operate">
                            <!--<a class="contrast" href="javascript:;"><i></i>对比</a>-->
                            <a class="buy" href="javascript:;"><i>￥</i>购买</a>
                            <a class="focus" href="javascript:;"><i></i>收藏</a>
                            <a class="addcart" href="javascript:;"><i></i>加入购物车</a>
                        </p>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
        <?php
    endforeach;
    ?>
</div>

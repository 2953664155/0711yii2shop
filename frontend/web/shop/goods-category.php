<div class="nav w1210 bc mt10">
    <!--  商品分类部分 start-->
    <div class="category fl"> <!-- 非首页，需要添加cat1类 -->
        <div class="cat_hd">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
            <h2>全部商品分类</h2>
            <em></em>
        </div>
        <div class="cat_bd">
            <?php foreach ($goods_category as $v):?>
                <div class="cat item1">
                    <h3><a href="content?id=<?=$v->id?>"><?=$v->name?></a> <b></b></h3>
                    <div class="cat_detail">
                        <?php foreach (\backend\models\GoodsCategory::find()->where(['parent_id'=>$v->id])->all() as $value):?>
                            <dl class="dl_1st">
                                <dt><a href="content?id=<?=$value->id?>"><?=$value->name?></a></dt>
                                <dd>
                                    <?php foreach (\backend\models\GoodsCategory::find()->where(['parent_id'=>$value->id])->all() as $row):?>
                                        <a href="content?id=<?=$row->id?>"><?=$row->name?></a>
                                    <?php endforeach;?>
                                </dd>
                            </dl>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>

    </div>
    <!--  商品分类部分 end-->

    <div class="navitems fl">
        <ul class="fl">
            <li class="current"><a href="">首页</a></li>
            <li><a href="">电脑频道</a></li>
            <li><a href="">家用电器</a></li>
            <li><a href="">品牌大全</a></li>
            <li><a href="">团购</a></li>
            <li><a href="">积分商城</a></li>
            <li><a href="">夺宝奇兵</a></li>
        </ul>
        <div class="right_corner fl"></div>
    </div>
</div>
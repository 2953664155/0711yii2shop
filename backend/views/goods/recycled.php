
    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>货号</th>
            <th>名称</th>
            <th>价格</th>
            <th>库存</th>
            <th>LOGO</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?=$v->id?></td>
                <td><?=$v->sn?></td>
                <td><?=$v->name?></td>
                <td><?=$v->shop_price?></td>
                <td><?=$v->stock?></td>
                <td>
                    <div class="">
                        <?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$v->logo,['width'=>80,'class'=>'img-thumbnail'])?>
                    </div>
                </td>
                <td>
                    <?=\yii\bootstrap\Html::a('相册',['add-img','id'=>$v->id])?>
                    <?=\yii\bootstrap\Html::a('恢复',['recover','id'=>$v->id])?>
                    <?=\yii\bootstrap\Html::a('清除',['clear','id'=>$v->id])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
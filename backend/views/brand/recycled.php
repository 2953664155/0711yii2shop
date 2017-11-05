
<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $v):?>
    <tr>
        <td><?=$v->id?></td>
        <td><?=$v->name?></td>
        <td>
            <div class="">
            <?=\yii\bootstrap\Html::img($v->logo,['width'=>80,'class'=>'img-thumbnail'])?>
            </div>
        </td>
        <td>
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

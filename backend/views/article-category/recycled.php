
    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?=$v->id?></td>
                <td><?=$v->name?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['recover','id'=>$v->id])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);


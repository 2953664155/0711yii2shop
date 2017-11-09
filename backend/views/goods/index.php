<div class="col-lg-1">
    <?=\yii\bootstrap\Html::a('添加','add',['class'=>'btn btn-info'])?>
</div>
    <form class="form-search" action="keywords">
        <div class="form-group col-lg-3">
            <input type="text" class="form-control" name="name" placeholder="名称">
        </div>
        <button type="submit" class="btn"><span class="glyphicon glyphicon-search">搜索</span></button>
    </form>
    <div style="position:absolute; right: 200px; top: 80px;}">
            <span class="glyphicon glyphicon-trash">
                <?=\yii\bootstrap\Html::a('回收站','recycled')?>
            </span>
    </div>
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
                    <?=\yii\bootstrap\Html::a('相册',['img','id'=>$v->id])?>
                    <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$v->id])?>
                    <?=\yii\bootstrap\Html::a('删除','javascript:;',['class'=>'del','id'=>$v->id])?>
                    <?=\yii\bootstrap\Html::a('预览',['preview','id'=>$v->id])?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager
]);
$url = \yii\helpers\Url::to('del');
$this->registerJs(
    <<<JS
        $('.del').click(function() {
                if(confirm('是否删除该记录?删除后可在回收站中回复')){
                    var url = '{$url}';
                    var id = $(this).attr('id');
                    var that = this;
                    $.post(url,{id:id},function(data) {
                       if(data == '删除成功'){
                            $(that).closest('tr').fadeOut();
                       }else{
                            alert(data);
                       }
                    });
                }
        });
JS

);

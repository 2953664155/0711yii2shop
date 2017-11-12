<div class="col-lg-1">
    <?=\yii\bootstrap\Html::a('添加','add',['class'=>'btn btn-info'])?>
</div>
    <table class="table table-hover">
        <tr>
            <th>名称</th>
            <th>路由</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?= $v->name = str_repeat('——',($v->tier)*2).$v->name;?></td>
                <td><?=$v->route?></td>
                <td><?=$v->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$v->id])?>
                    <?php
                        if(Yii::$app->user->can('menu/del')){
                            echo \yii\bootstrap\Html::a('删除','javascript:;',['class'=>'del','id'=>$v->id]);
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
//echo \yii\widgets\LinkPager::widget([
//    'pagination'=>$pager
//]);
$url = \yii\helpers\Url::to('del');
$this->registerJs(
    <<<JS
        $('.del').click(function() {
                if(confirm('是否删除该记录?删除后不可恢复!!!')){
                    var url = '{$url}';
                    var id = $(this).attr('id');
                    var that = this;
                    $.post(url,{id:id},function(data) {
                       if(data == '1'){
                            $(that).closest('tr').fadeOut();
                       }else{
                            alert(data);
                       }
                    });
                }
        });
JS

);

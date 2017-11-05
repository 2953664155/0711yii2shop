<?=\yii\bootstrap\Html::a('添加','add',['class'=>'btn btn-info'])?>
    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td><?=$v->id?></td>
                <td><?= $v->name = str_repeat('——',($v->depth)*2).$v->name;?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['edit','id'=>$v->id])?>
                    <?=\yii\bootstrap\Html::a('删除','javascript:;',['class'=>'del','id'=>$v->id])?>
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
                if(confirm('是否删除该记录?删除不可恢复')){
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

<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/media/js/jquery.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);


?>
<!--第二步：添加如下 HTML 代码-->
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($permission as $v):?>
    <tr>
        <td><?=$v->name?></td>
        <td><?=$v->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['edit-permission','name'=>$v->name])?>
            <?=\yii\bootstrap\Html::a('删除','javascript:;',['class'=>'del','id'=>$v->name])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
$url = \yii\helpers\Url::to(['del-permission']);
$this->registerJs(
        <<<JS
    $(document).ready( function () {
            $('#table_id_example').DataTable();
        } );
   $(".del").click(function() {
        if(confirm("是否删除?删除后不可恢复!!!")){
            var url = '{$url}';
            var name = $(this).attr('id');
            var that = this;
            $.post(url,{name:name},function(data) {
              if(data == 1){
                  $(that).closest('tr').fadeOut();
              }else {
                  alert(data);
              }
            })
        }
   })
JS
)
?>


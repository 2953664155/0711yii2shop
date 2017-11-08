
<?php
//==========上传图片===================
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<?php
$url =  \yii\helpers\Url::to(['test']);
$url2 =  \yii\helpers\Url::to(['add-img']);
$this->registerJs(
    <<<JS
        // 初始化Web Uploader
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf:'/js/Uploader.swf',
            // 文件接收服务端。
            server:"{$url}",
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png,'
            }
        });
        // 文件上传成功保存到数据库
        uploader.on( 'uploadSuccess', function( file ,response) {
                 $.post('add-img',{goods_id:'{$goods_id}',path:response.url},function(data) {
                     if (data == '添加成功'){
                         location.reload();
                     }else {
                         alert(data);
                     }
                 });
        });
JS
);
?>
    <table class="table table-hover">
        <tr>
            <th>LOGO</th>
        </tr>
        <?php foreach ($model as $v):?>
            <tr>
                <td>
                    <div class="img">
                        <?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$v->path,['width'=>450,'class'=>'img-thumbnail','id'=>$v->id])?>
                        <span id="mig"></span>
                    </div>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
<?php
$url = \yii\helpers\Url::to('delete');
$this->registerJs(
    <<<JS
        $('.img-thumbnail').dblclick(function() {
                if(confirm('是否删除该记录?删除后不可恢复')){
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

<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
//===============webuploader=======================
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',['depends'=>\yii\web\JqueryAsset::className()]);
$url =  \yii\helpers\Url::to(['upload']);
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
    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ,response) {
        $('#img').attr('src',response.url);//上传成功回显图片
        $("#brandform-logo").val(response.url);//将图片写入logo
    });
JS
);
?>
    <!--dom结构部分-->
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
    </div>
    <div>
        <img id="img" width="80" src=""/>
    </div>
<?php
//================================================
echo $form->field($model,'status',['inline'=>1])->radioList(['0'=>'隐藏','1'=>'显示']);
echo \yii\bootstrap\Html::submitInput('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

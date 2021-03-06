<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'logo')->hiddenInput();
//==========上传图片===================
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',['depends'=>\yii\web\JqueryAsset::className()]);
$url =  \yii\helpers\Url::to(['test']);
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
        $("#goods-logo").val(response.url);//将图片写入logo
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
echo $form->field($model,'goods_category_id')->hiddenInput();
//==========商品分类===================
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$nodes = \yii\helpers\Json::encode(\yii\helpers\ArrayHelper::merge([['id'=>0,'goods_category_id'=>0,'name'=>'顶级分类']],\backend\models\GoodsCategory::getZtreeNodes()));
$this->registerJs(
    <<<JS
var zTreeObj;
        var setting = {
            callback:{
                onClick: function(event, treeId, treeNode){
                    //获取被点击节点的id
                    var id= treeNode.id;
                    //将id写入parent_id的值
                    $("#goods-goods_category_id").val(id);
                }
            }
            ,
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
        
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开所有节点
        zTreeObj.expandAll(true);
        //选中节点(回显)   
        //获取节点  ,根据节点的id搜索节点
        var node = zTreeObj.getNodeByParam("id", {$category->parent_id}, null);   
        zTreeObj.selectNode(node);
        
JS

);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
//=========================================
echo $form->field($model,'brand_id')->dropDownList(\backend\models\Brand::getBrand());
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'is_on_sale',['inline'=>1])->radioList(['0'=>'下架','1'=>'在售']);
echo $form->field($model,'sort')->textInput();

//============商品详情=======================
echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[]);
//============================================
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
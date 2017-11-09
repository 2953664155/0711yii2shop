<?php
namespace backend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\UploadedFile;
class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    //添加商品
    public function actionAdd()
    {
        //显示表单
        $model = new Goods();
        $category = new GoodsCategory();
        $content = new GoodsIntro();
        $get_count = new GoodsDayCount();
        $category->parent_id = 0;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate() & $content->validate()){
                $day =date('Y-m-d',time());
                $time =date('Ymd',time());
                $count = GoodsDayCount::findOne(['day'=>$day]);//根据时间查找该数据
                if($count){//如果存在数据
                    $count->count = str_pad($count->count+1,5,"0",STR_PAD_LEFT);
                    $count->save();
                    $model->sn = $time.$count->count;
                }else{//如果不存在添加数据
                    $get_count->count = 1;
                    $get_count->day = $day;
                    $get_count->save();
                    $model->sn = $time.$get_count->count;
                }
                $model->status = 1;
                $model->create_time = time();
                $model->save(false);
                $content->goods_id = $model->id;
                $content->save(0);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model,'content'=>$content,'category'=>$category]);
    }
    //修改商品
    public function actionEdit($id){
        //显示表单
        $model = Goods::findOne($id);
        $category = GoodsCategory::findOne(['id'=>$model->goods_category_id]);
        $content = GoodsIntro::findOne(['goods_id'=>$id]);
        $get_count = new GoodsDayCount();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate() & $content->validate()){
                $day =date('Y-m-d',time());
                $time =date('Ymd',time());
                $count = GoodsDayCount::findOne(['day'=>$day]);//根据时间查找该数据
                if($count){//如果存在数据
                    $count->count = str_pad($count->count+1,5,"0",STR_PAD_LEFT);
                    $count->save();
                    $model->sn = $time.$count->count;
                }else{//如果不存在添加数据
                    $get_count->count = 1;
                    $get_count->day = $day;
                    $get_count->save();
                    $model->sn = $time.$get_count->count;
                }
                $model->status = 1;
                $model->create_time = time();
                $model->save(false);
                $content->goods_id = $model->id;
                $content->save(0);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model,'content'=>$content,'category'=>$category]);
    }
    //ueditor插件
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    //处理上传图片
    public function actionTest(){
        if(\Yii::$app->request->isPost){
            $imageFile = UploadedFile::getInstanceByName('file');
            //判断文件是否上传
            if($imageFile) {
                $ext = $imageFile->extension;//获取文件的后缀名
                $time = date('Ymd', time());
                if (!is_dir('upload/' . $time)) {//判断文件是否存在
                    mkdir('upload/' . $time);
                }
                $filename = '/upload/' . $time . '/' . uniqid() . '.' . $ext;
                $imageFile->saveAs(\Yii::getAlias('@webroot') . $filename, 0);
                return Json::encode(['url'=>$filename]);
            }
            //=============================================
        }else{
            return '文件上传失败';
        }
    }
    //商品列表
    public function actionIndex(){
        $pager = new Pagination();
        $query = Goods::find()->where(['status'=>1]);//正常数据
        $pager->totalCount = $query->count();
        $pager->pageSize = 5;
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //删除商品
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Goods::updateAll(['status'=>0],['id'=>$id]);
        if ($model){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    //回收站
    public function actionRecycled(){
        $pager = new Pagination();
        $query = Goods::find()->where(['status'=>0]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('recycled',['model'=>$model,'pager'=>$pager]);
    }
    //恢复
    public function actionRecover($id){
        $model = Goods::updateAll(['status'=>1],['id'=>$id]);
        if ($model){
            \Yii::$app->session->setFlash('success','恢复成功');
            return $this->redirect('index');
        }else{
            \Yii::$app->session->setFlash('success','恢复失败');
            return $this->redirect('recycled');
        }
    }
    //相册
    public function actionImg($id){
        $model = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->render('img',['model'=>$model,'goods_id'=>$id]);
    }
    //添加图片
    public function actionAddImg(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $gallery = new GoodsGallery();
            $gallery->goods_id = $request->post()['goods_id'];
            $gallery->path = $request->post()['path'];
            $gallery->save();
            echo "添加成功";
        }else{
            echo "添加失败";
        }
    }
    //删除图片
    public function actionDelete(){
        $id = \Yii::$app->request->post('id');
        //根据ID删除数据
        $model = \Yii::$app->db->createCommand()->delete('goods_gallery',['id'=>$id])->execute();
        if ($model){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    //商品详情表
    public function actionPreview($id){
        $content = GoodsIntro::findOne(['goods_id'=>$id]);
        $img = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $goods_name = Goods::findOne($id)->name;
        return $this->render('preview',['content'=>$content,'img'=>$img,'goods_name'=>$goods_name]);
    }
    //搜索
    public function actionKeywords(){
        $request = \Yii::$app->request;
        $query = Goods::find()->andWhere(['like', 'name',$request->get()['name']]);
        if($query){
            $pager = new Pagination();
            $pager->totalCount = $query->count();
            $pager->pageSize = 5;
            $model = $query->limit($pager->limit)->offset($pager->offset)->all();
            return $this->render('index',['model'=>$model,'pager'=>$pager]);
        }else{
            return $this->redirect('index');
        }
    }
}
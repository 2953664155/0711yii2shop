<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\BrandForm;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\UploadedFile;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    //品牌列表
    public function actionIndex()
    {
        $pager = new Pagination();
        $query = Brand::find()->where(['status'=>[0,1]]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);

    }
    //添加品牌
    public function actionAdd(){
        $request =  \Yii::$app->request;
        $model = new BrandForm();
        $brand  = new Brand();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $brand->name = $model->name;
                $brand->intro = $model->intro;
                $brand->logo = $model->logo;
                $brand->status = $model->status;
                $brand->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }else {
                var_dump($model->getErrors());
            }
        }
        //显示表单
        return $this->render('add',['model'=>$model]);
    }
    //修改品牌
    public function actionEdit($id){
        $model = Brand::findOne($id);
        $request =  \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }else {
                var_dump($model->getErrors());
            }
        }
        //显示表单
        return $this->render('add',['model'=>$model]);
    }
    //删除品牌
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Brand::updateAll(['status'=>-1],['id'=>$id]);
        if ($model){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    //回收站
    public function actionRecycled(){
        $pager = new Pagination();
        $query = Brand::find()->where(['status'=>-1]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('recycled',['model'=>$model,'pager'=>$pager]);
    }
    //恢复
    public function actionRecover($id){
        $model = Brand::updateAll(['status'=>1],['id'=>$id]);
        if ($model){
            \Yii::$app->session->setFlash('success','恢复成功');
            return $this->redirect('index');
        }else{
            \Yii::$app->session->setFlash('success','恢复失败');
            return $this->redirect('recycled');
        }
    }
    //清除
    public function actionClear($id){
        //根据ID删除数据
        \Yii::$app->db->createCommand()->delete('brand',['id'=>$id])->execute();
        \Yii::$app->session->setFlash('success','清除成功');
        //跳转至列表
        return $this->redirect('recycled');
    }
    //处理上传图片
    public function actionUpload(){
        if(\Yii::$app->request->isPost){
            $imageFile = UploadedFile::getInstanceByName('file');
            //判断文件是否上传
            if($imageFile){
                $ext = $imageFile->extension;//获取文件的后缀名
                $time = date('Ymd',time());
                if(!is_dir('upload/'.$time)){//判断文件是否存在
                    mkdir('upload/'.$time);
                }
                $filename = '/upload/'.$time.'/'.uniqid().'.'.$ext;
                $imageFile->saveAs(\Yii::getAlias('@webroot').$filename,0);
                //==============上传到七牛云===================
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="37HrpQzOc8FCMFhT83cBBkiKLjPW-HQdMtmGdLb7";
                $secretKey = "W2hJBw-xM5foMzYqT5gwXQ5KrgLaobg7wUkeLsbG";
                $bucket = "php20170711";
                $domian = 'oyxzdtxu5.bkt.clouddn.com';
                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);
                // 生成上传 Token
                $token = $auth->uploadToken($bucket);
                // 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot').$filename;
                // 上传到七牛后保存的文件名
                $key = $filename;
                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();
                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                if ($err !== null) {
                    return Json::encode(['err'=>$err]);
                } else {
                    return Json::encode(['url'=>'http://'.$domian.'/'.$filename]);
                }
            }
                //=============================================
            }else{
                return '文件上传失败';
            }
        }
}

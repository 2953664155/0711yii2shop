<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\BrandForm;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
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
            $model->file = UploadedFile::getInstance($model,'file');
            if($model->validate()){
                $ext = $model->file->extension;//获取文件的后缀名
                $time = date('Ymd',time());
                if(!is_dir('/upload/'.$time)){//判断文件是否存在
                    mkdir('/upload/'.$time);
                }
                $filename = 'upload/'.$time.'/'.uniqid().'.'.$ext;
                $model->file->saveAs($filename);
                $brand->name = $model->name;
                $brand->intro = $model->intro;
                $brand->logo = $filename;
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
        $filepath = $model->logo;//原图片路径
        if($request->isPost){
            $model->load($request->post());
            $model->file = UploadedFile::getInstance($model,'file');
            if($model->validate()){
                unlink($filepath);//删除原图片
                $ext = $model->file->extension;//获取文件的后缀名
                $time = date('Ymd',time());
                if(!is_dir('/upload/'.$time)){//判断文件是否存在
                    mkdir('/upload/'.$time);
                }
                $filename = 'upload/'.$time.'/'.uniqid().'.'.$ext;
                $model->file->saveAs($filename);
                $model->logo = $filename;
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

}

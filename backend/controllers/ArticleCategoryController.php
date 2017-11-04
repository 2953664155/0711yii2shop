<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use backend\models\ArticleCategoryForm;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    //文章分类列表
    public function actionIndex()
    {
        $pager = new Pagination();
        $query = ArticleCategory::find()->where(['status'=>[0,1]]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //添加文章分类
    public function actionAdd(){
        $request =  \Yii::$app->request;
        $model = new ArticleCategoryForm();
        $brand  = new ArticleCategory();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $brand->name = $model->name;
                $brand->intro = $model->intro;
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
    //修改文章分类
    public function actionEdit($id){
        $model = ArticleCategory::findOne($id);
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
    //删除文章分类
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = ArticleCategory::updateAll(['status'=>-1],['id'=>$id]);
        if ($model){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    //回收站
    public function actionRecycled(){
        $pager = new Pagination();
        $query = ArticleCategory::find()->where(['status'=>-1]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('recycled',['model'=>$model,'pager'=>$pager]);
    }
    //恢复
    public function actionRecover($id){
        $model = ArticleCategory::updateAll(['status'=>1],['id'=>$id]);
        if ($model){
            \Yii::$app->session->setFlash('success','恢复成功');
            return $this->redirect('index');
        }else{
            \Yii::$app->session->setFlash('success','恢复失败');
            return $this->redirect('recycled');
        }
    }
}

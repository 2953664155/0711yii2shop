<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    //商品分类列表
    public function actionIndex()
    {
        $pager = new Pagination();
        $query = GoodsCategory::find();//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 5;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //添加商品分类
    public function actionAdd(){
        $model = new GoodsCategory();
        $request = \Yii::$app->request;
        $model->parent_id = 0;
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if ($model->parent_id == 0){
                    $model->makeRoot();
                    \Yii::$app->session->setFlash('success','添加'.$model->name.'顶级分类成功');
                    return $this->redirect('index');
                }else{
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    \Yii::$app->session->setFlash('success','添加'.$model->name.'子分类成功');
                    return $this->redirect('index');
                }

            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除商品分类
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        //根据ID删除数据
        \Yii::$app->db->createCommand()->delete('goods_category',['id'=>$id])->execute();
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转至列表
        return $this->redirect('index');
    }
    //修改商品分类
    public function actionEdit($id){
        $model = GoodsCategory::findOne($id);
        $request = \Yii::$app->request;
        $parent = $model->parent_id;
        if ($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if ($model->parent_id == $parent){
                    \Yii::$app->session->setFlash('success','已经在改分类下!');
                    return $this->redirect('add');
                }else if($model->parent_id == $id){
                    \Yii::$app->session->setFlash('success','不能将改分类修改到改分类下!');
                    return $this->redirect('add');
                }
                if ($model->parent_id == 0){
                    $model->makeRoot();
                    \Yii::$app->session->setFlash('success','添加'.$model->name.'顶级分类成功');
                    return $this->redirect('index');
                }else{
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    \Yii::$app->session->setFlash('success','添加'.$model->name.'子分类成功');
                    return $this->redirect('index');
                }

            }
        }
        return $this->render('add',['model'=>$model]);
    }
}

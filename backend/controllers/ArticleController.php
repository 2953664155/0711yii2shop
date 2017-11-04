<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use backend\models\ArticleForm;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    //文章列表
    public function actionIndex()
    {
        $pager = new Pagination();
        $query = Article::find()->where(['status'=>[0,1]]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //添加文章
    public function actionAdd(){
        $request =  \Yii::$app->request;
        $model = new ArticleForm();
        $article = new Article();
        $article_detail = new ArticleDetail();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $article->name = $model->name;
                $article->intro = $model->intro;
                $article->status = $model->status;
                $article->article_category_id = $model->article_category_id;
                $create_time = time();
                $article->create_time = $create_time;
                $article->save(false);
                $id = Article::find()->where(['create_time' =>$create_time])->one()->id;//根据添加时间找到文章的ID
                $article_detail->article_id = $id;
                $article_detail->content = $model->content;
                $article_detail->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }else {
                var_dump($model->getErrors());
            }
        }
        //显示表单
        return $this->render('add',['model'=>$model]);
    }
    //修改文章
    public function actionEdit($id){
        $request =  \Yii::$app->request;
        $model = Article::findOne($id);
        $article_detail = ArticleDetail::findOne($id);
        $model->content = $article_detail->content;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                $article_detail->content = $model->content;
                $article_detail->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }else {
                var_dump($model->getErrors());
            }
        }
        //显示表单
        return $this->render('add',['model'=>$model]);
    }
    //删除文章
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Article::updateAll(['status'=>-1],['id'=>$id]);
        if ($model){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    //回收站
    public function actionRecycled(){
        $pager = new Pagination();
        $query = Article::find()->where(['status'=>-1]);//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 3;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('recycled',['model'=>$model,'pager'=>$pager]);
    }
    //恢复
    public function actionRecover($id){
        $model = Article::updateAll(['status'=>1],['id'=>$id]);
        if ($model){
            \Yii::$app->session->setFlash('success','恢复成功');
            return $this->redirect('index');
        }else{
            \Yii::$app->session->setFlash('success','恢复失败');
            return $this->redirect('recycled');
        }
    }

}

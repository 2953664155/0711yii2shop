<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\data\Pagination;

class GoodsCategoryController extends CommonController
{
    //商品分类列表
    public function actionIndex()
    {
        $pager = new Pagination();
        $query = GoodsCategory::find();//所有数据
        $pager->totalCount = $query->count();//总条数
        $pager->pageSize = 100;//每页显示数
        $model = $query->limit($pager->limit)->offset($pager->offset)->orderBy('tree ASC,lft ASC')->all();
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
                    return $this->redirect('add');
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
        $goods_category = GoodsCategory::findOne(['parent_id'=>$id]);
        $goods = Goods::find()->where(['goods_category_id'=>$id])->all();
        if($goods_category !== null){
            echo "删除失败!改节点下还有节点";
        }elseif ($goods !== []){
            echo "删除失败!改商品分类才下还有商品";
        }else{
            //根据ID删除数据
            \Yii::$app->db->createCommand()->delete('goods_category',['id'=>$id])->execute();
            \Yii::$app->session->setFlash('success','删除成功');
            //跳转至列表
            echo "删除成功";
        }

    }
    //修改商品分类
    public function actionEdit($id){
        $model = GoodsCategory::findOne($id);
        $request = \Yii::$app->request;
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

}

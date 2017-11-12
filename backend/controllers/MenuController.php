<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class MenuController extends CommonController
{
    //添加菜单
   public function actionAdd(){
       $model = new Menu();
       $menus = Menu::find()->where(['parent_id'=>0])->all();
       $menu = ArrayHelper::map($menus,'id','name');
       $request = \Yii::$app->request;
       if ($request->isPost) {
           $model->load($request->post());
           if ($model->validate()) {
               if ($model->parent_id == 0){
                   $model->tier = 0;
               }else{
                   $model->tier = 1;
               }
               $model->save();
               \Yii::$app->session->setFlash('success','添加成功');
               $this->redirect('index');
           } else {
               var_dump($model->getErrors());
           }
       }
       return $this->render('add',['model'=>$model,'menu'=>$menu]);
   }
   //菜单列表
    public function actionIndex(){
       $menus = Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $model[] = $menu;
            foreach ($menu->children as $v){
                $model[] = $v;
            }
        }
        return $this->render('index',['model'=>$model]);
    }
    //删除菜单
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Menu::deleteAll(['id'=>$id]);
        if($model){
            echo 1;
        }else{
            echo 2;
        }
    }
    //修改菜单
    public function actionEdit($id){
        $model = Menu::findOne($id);
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        $menu = ArrayHelper::map($menus,'id','name');
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->parent_id == 0){
                    $model->tier = 0;
                }else{
                    $model->tier = 1;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index');
            } else {
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model,'menu'=>$menu]);
    }

}

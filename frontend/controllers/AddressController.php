<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/14
 * Time: 9:36
 */

namespace frontend\controllers;


use frontend\models\Address;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class AddressController extends Controller
{
    public $enableCsrfValidation = false;
    //地址
    public function actionAddress(){
        $model = new Address();
        $request = \Yii::$app->request;
        $user_id = \Yii::$app->user->id;
        if($request->isPost){
            if ($request->post('id') == ''){
                $model->load($request->post(),'');
            }else{
                $id = $request->post('id');
                $model = Address::findOne($id);
                $model->load($request->post(),'');
            }
//            var_dump($request->post('id'));exit;
            $model->load($request->post(),'');
            if ($model->validate()){
                $model->province = $request->post('cmbProvince');
                $model->city = $request->post('cmbCity');
                $model->count = $request->post('cmbArea');
                $model->user_id = $user_id;
                $model->save();
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        $address = Address::find()->where(['user_id'=>$user_id])->all();
        return $this->render('address',['address'=>$address]);
    }
    //删除地址
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Address::deleteAll(['id'=>$id]);
        if($model){
            echo 1;
        }else{
            echo 2;
        }
    }
    //修改地址
    public function actionEdit()
    {
        $id = \Yii::$app->request->post('id');
        $address = Address::findOne($id);
        if($address){
            echo Json::encode($address);
        }else{
            echo 0;
        }

    }
}
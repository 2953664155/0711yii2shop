<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/11
 * Time: 20:34
 */

namespace backend\controllers;


use backend\models\LoginForm;
use yii\web\Controller;

class LoginController extends Controller
{
    //登录
    public function actionLogin(){
        //实例化表单模型
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            //表单提交,接收表单数据
            $model->load($request->post());
            if($model->validate()){
                //验证账号密码是否正确
                if($model->login($model->cookie)){
                    //提示信息  跳转
                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['user/index']);
                }
            }
        }
        //显示表单
        return $this->render('login',['model'=>$model]);
    }
}
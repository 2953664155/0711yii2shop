<?php

namespace frontend\controllers;



use common\models\LoginForm;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    //注册用户
    public function actionAdd(){
        $model = new Member();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();
                $model->save();

                return $this->redirect(['member/add']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add');
    }
    //登陆
    public function actionLogin(){
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
//            var_dump($model);exit;
            if($model->validate()){
                if($model->login()){
                    //成功跳转
                    \Yii::$app->session->setFlash('success','登陆成功');
                    return $this->redirect('index');
                } else {
                    return $this->redirect('login');
                }
            }else{
                echo "1";exit;
            }
        }
        //显示页面
        return $this->render('login');
    }
    //验证用户唯一性
    public function actionCheckName($username){
        $model= Member::findOne(['username'=>$username]);
        if($model){
            return 'false';
        }
        return 'true';
    }
    //验证邮箱唯一性
    public function actionCheckEmail($email){
        $model= Member::findOne(['email'=>$email]);
        if($model){
            return 'false';
        }
        return 'true';
    }
    //验证手机唯一性
    public function actionCheckTel($tel){
        $model= Member::findOne(['tel'=>$tel]);
        if($model){
            return 'false';
        }
        return 'true';
    }
}

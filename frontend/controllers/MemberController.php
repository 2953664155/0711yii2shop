<?php

namespace frontend\controllers;



use Codeception\Module\Redis;
use frontend\component\Sms;
use frontend\models\Cart;
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
                return $this->redirect(['member/login']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add');
    }
    //登陆
    public function actionLogin(){
        $model = new \frontend\models\LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                if($model->login($model->cookie)){
                    $cookies = \Yii::$app->request->cookies;
                    $carts = unserialize($cookies->getValue('carts'));
                    if(!$carts){
                        $carts = [];
                    }
                    foreach($carts as $k=>$v){
                        $model = Cart::find()->where(['goods_id'=>$k])->andWhere(['member_id'=>\Yii::$app->user->id])->one();
                        if($model){
                            $model->amount += $v;
                            $model->save();
                        }else{
                            $cart = new Cart();
                            $cart->goods_id = $k;
                            $cart->amount = $v;
                            $cart->member_id = \Yii::$app->user->id;
                            $cart->save();
                        }
                    }
                    \Yii::$app->response->cookies->remove('carts');
                    //成功跳转
                    \Yii::$app->session->setFlash('success','登陆成功');
                    return $this->redirect(['goods-category/index']);
                } else {
                    return $this->redirect('login');
                }
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
    //ajax发送短信
    public function actionAjaxSms($phone){
        //接收手机号码   发送短信
        $num  = rand(1000,9999);
        $response = Sms::sendSms(
            "奔跑的猪儿虫", // 短信签名
            "SMS_109515446", // 短信模板编号
            "{$phone}", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$num,
            )
        );
        if ($response->Code == "OK"){
            //保存验证码到REDIS中
            $redis = new \Redis();
            $redis->connect('127.0.0.1',6379);
            $redis->set('captcha'.$phone,$num,10*60);
            echo 1;
        }else{
            echo 0;
        }
    }
    //ajax验证短信
    public function actionCheckSms($captcha,$tel){
        //从redis中却出数据验证
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $code = $redis->get('captcha'.$tel);
        if($code == $captcha){
            return 'true';
        }else{
            return 'false';
        }
    }
    //注销登录
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect('login');
    }
}

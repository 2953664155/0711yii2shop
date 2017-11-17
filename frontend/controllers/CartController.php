<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/16
 * Time: 0:47
 */

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;

class CartController extends Controller
{
    public $enableCsrfValidation = false;
    //购物车列表
    public function actionIndex(){
        if(\Yii::$app->user->isGuest){//未登录
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);
            }else{
                $carts = [];
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }else{//登录
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $carts = ArrayHelper::map($carts,'goods_id','amount');
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }
        return $this->render('flow',['models'=>$models,'carts'=>$carts]);
    }
    //删除购物车
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        if (\Yii::$app->user->isGuest){//未登录
            $cookies = \Yii::$app->request->cookies;
            $carts = unserialize($cookies->getValue('carts'));
            unset($carts[$id]);
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);
            echo 1;
        }else{//登录
            $model = Cart::deleteAll(['goods_id'=>$id]);
            if ($model){
                echo 1;
            }else{
                echo 0;
            }
        }

    }
    //AJAX操作购物车
    public function actionAjaxCart($type){
        //登录操作数据库 未登录操作cookie
        switch ($type){
            case 'change'://修改购物车
                $goods_id = \Yii::$app->request->post('goods_id');
                $amount = \Yii::$app->request->post('amount');
                if(\Yii::$app->user->isGuest){
                    //取出cookie中的购物车
                    $cookies = \Yii::$app->request->cookies;
                    $carts = $cookies->getValue('carts');
                    if($carts){
                        $carts = unserialize($carts);
                    }else{
                        $carts = [];
                    }
                    //修改购物车商品数量
                    $carts[$goods_id] = $amount;
                    //保存cookie
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookies->add($cookie);
                }else{
                    $cart = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>\Yii::$app->user->id])->one();
                    $cart->amount = $amount;
                    $cart->save();
                }
                break;
            case 'del':

                break;
        }
    }
}
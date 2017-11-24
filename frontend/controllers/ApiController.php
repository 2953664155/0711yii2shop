<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/23
 * Time: 11:17
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    public function init(){//接口返回数据格式
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $result = [
            'error'=> null,
            'msg'=> '',
            'data'=>[]
        ];
    }
    //会员登录
    public function actionLogin(){
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');
        $member = Member::findOne(['username'=>$username]);
        if ($member){
            if (\Yii::$app->security->validatePassword($password,$member->password_hash)){
                \Yii::$app->user->login($member);
                $member->last_login_time = time();
                $member->last_login_ip = \Yii::$app->request->userIP;
                $member->save();
                $result['msg']='登录成功';
                $result['data'] = [
                    'member_id'=>$member->id,
                    'username'=>$member->username,
                    'email'=>$member->email,
                    'tel'=>$member->tel
                ];
            }else{
                $result['error'] = 1;
                $result['msg'] = "密码错误";
            }
        }else{
            $result['error'] = 0;
            $result['msg'] = "用户不存在";
        }
        return $result;
    }
    //会员注册
    public function actionRegister(){
        $model = new Member();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();
                $model->save();
                $result['msg'] = "注册成功";
                $result['data'] = [
                    'member_id' => $model->id,
                    'username' => $model->username,
                    'email' => $model->email,
                    'tel' => $model->tel
                ];
            } else {
                $result['error'] = 1;
                $result['msg'] = $model->getErrors();
            }
        }else{
            $result['error'] = 2;
            $result['msg'] = "请求方式错误";
        }
            return $result;
    }
    //修改密码
    public function actionPassword(){

    }
    //获取当前登录用户的信息
    public function actionGetMember(){
        $member_id = \Yii::$app->user->id;
        $member = Member::findOne(['id'=>$member_id]);
        if ($member){
            $result = [
                'msg'=>"获取成功",
                'data'=>[
                    'username'=>$member->username,
                    'email'=>$member->email,
                    'tel'=>$member->tel,
                    'last_login_time'=>$member->last_login_time,
                    'last_login_ip'=>$member->last_login_ip,
                ]
            ];
        }else{
            $result = [
                'error'=>1,
                'msg'=>"未登录"
            ];
        }

        return $result;
    }

    //添加收货地址
    public function actionAddAddress(){
        $model = new Address();
        $request = \Yii::$app->request;
        $user_id = \Yii::$app->user->id;
        if($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
                $model->province = $request->post('cmbProvince');
                $model->city = $request->post('cmbCity');
                $model->count = $request->post('cmbArea');
                $model->user_id = $user_id;
                $model->save();
                $result = [
                    'msg'=> "添加成功",
                    'data'=>[
                        'name'=>$model->name,
                        'province'=>$model->province,
                        'city'=>$model->city,
                        'count'=>$model->count,
                        'detailed_address'=>$model->detailed_address,
                        'phone'=>$model->phone
                    ]
                ];
            } else {
                $result = [
                    'error'=>1,
                    'msg'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'error'=>2,
                'msg'=>'请求错误'
            ];
        }
        return $result;
    }
    //修改收货地址
    public function actionUpdateAddress($id){
        $model = Address::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->province = $request->post('cmbProvince');
                $model->city = $request->post('cmbCity');
                $model->count = $request->post('cmbArea');
                $model->save();
                $result = [
                    'msg'=>"修改成功",
                    'data'=>$model
                ];
            }else{
                $result = [
                    'error'=>1,
                    'msg'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'error'=>2,
                'msg'=>'请求错误'
            ];
        }
        return $result;
    }
    //删除收货地址
    public function actionDelAddress(){
        $id = \Yii::$app->request->post('id');
        $model = Address::deleteAll(['id'=>$id]);
        if($model){
            $result = [
                'msg'=>'删除成功'
            ];
        }else{
            $result = [
                'error'=>1,
                'msg'=>'删除失败或已被删除'
            ];
        }
        return $result;
    }
    //收货地址列表
    public function actionAddress(){
        $model = Address::find()->all();
        $result = [
            'data'=>$model
        ];
        return $result;
    }

    //获取所有商品分类
    public function actionGoodsCategory(){
        $model = GoodsCategory::find()->orderBy('tree ASC,lft ASC')->all();
        $result = [
            'data'=>$model,
        ];
        return $result;
    }
    //获取某分类的所有子分类
    public function actionGoodsCategoryChildren($id){

    }
    //获取某分类的父分类
    public function actionGoodsCategoryParent($id){

    }

    //获取某分类下面的所有商品
    public function actionCategoryGoods($id){
        $goods_category = GoodsCategory::find()->where(['id'=>$id])->one();
        if($goods_category->depth == 2){
            $model = Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
            $ids = $goods_category->Children()->andWhere(['depth'=>2])->column();
            $model = Goods::find()->where(['in','goods_category_id',$ids])->all();
        }
        $result = [
            'data'=>$model,
        ];
        return $result;
    }
    //获取某品牌下面的所有商品
    public function actionBrandGoods($id){
        $model = Goods::find()->where(['brand_id'=>$id])->all();
        $result = [
            'data'=>$model
        ];
        return $result;
    }
//==========================================================
    //获取文章分类
    public function actionArticleCategory(){

    }
    //获取某分类下面的所有文章
    public function actionArticleCategoryArticle(){

    }
    //获取某文章所属分类
    public function actionArticleArticleCategory(){

    }

    //添加商品到购物车
    public function actionAddOrder(){

    }
    //修改购物车某商品数量
    public function actionUpdateOrder(){

    }
    //删除购物车某商品
    public function actionDelOrder(){

    }
    //清空购物车
    public function actionEmpty(){

    }
    //获取购物车所有商品
    public function actionOrderGoods(){

    }














}
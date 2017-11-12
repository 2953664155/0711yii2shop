<?php

namespace backend\controllers;

use backend\models\PasswordForm;
use backend\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class UserController extends CommonController
{
    //添加管理员
    public function actionAdd(){
        //显示表单
        $model = new User();
        $request = \Yii::$app->request;
        $auth = \Yii::$app->authManager;
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($model->password_hash);
                $model->save();
                foreach ($model->role as $assign){
                    $auth->assign($auth->getRole($assign),$model->id);
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }else{
                var_dump($model->getErrors());
            }
        }
        $role = $auth->getRoles();
        $role = ArrayHelper::map($role,'name','description');
        return $this->render('add',['model'=>$model,"role"=>$role]);
    }
    //修改管理员
    public function actionEdit($id){
        //显示表单
        $model = User::findOne($id);
        $model->password_hash = '';
        $auth = \Yii::$app->authManager;
        $assigns = $auth->getAssignments($id);
        foreach ($assigns as $v){
            $assign[] = $v->roleName;
        }
        $model->role = $assign;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $model->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($model->password_hash);
                $model->save();
                $auth->revokeAll($id);//删除原用户的角色
                foreach ($model->role as $assign){
                    $auth->assign($auth->getRole($assign),$id);
                }
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }else{
                var_dump($model->getErrors());
            }
        }
        $role = $auth->getRoles();
        $role = ArrayHelper::map($role,'name','description');
        return $this->render('add',['model'=>$model,'role'=>$role]);
    }
    //管理员列表
    public function actionIndex()
    {
        $query = User::find();
        $pager = new Pagination();
        $pager->pageSize = 5;
        $pager->totalCount = $query->count();
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //删除
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        //根据ID删除数据
        $delete = \Yii::$app->db->createCommand()->delete('user',['id'=>$id])->execute();
        if($delete){
            echo "删除成功";
        }else{
            echo "删除失败";
        }
    }
    //修改密码
    public function actionPassword(){
        if (!\Yii::$app->user->isGuest){
            //显示修改表单
            $model = new PasswordForm();
            $request = \Yii::$app->request;
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){
                    //判断旧密码是否正确
                    $password_hash = \Yii::$app->user->identity->password_hash;
                    if (\Yii::$app->security->validatePassword($model->old_pwd,$password_hash)){
                        //旧密码正确
                        User::updateAll([
                            'password_hash'=>\Yii::$app->security->generatePasswordHash($model->confirm_pwd)
                        ],
                            [
                                'id'=>\Yii::$app->user->identity->id
                            ]);
                        \Yii::$app->user->logout();
                        \Yii::$app->session->setFlash('success','账号过期,请重新登录');
                        return $this->redirect(['login']);
                    }else{
//旧密码不正确
                        $model->addError('oldPassword','旧密码不正确');
                    }
                }else{
                    var_dump($model->getErrors());
                }
            }
            return $this->render('edit',['model'=>$model]);
        }else{
            \Yii::$app->session->setFlash('success','请先登录');
            return $this->redirect(['login']);
        }
    }
}

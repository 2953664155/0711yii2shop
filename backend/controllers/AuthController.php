<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/9
 * Time: 10:53
 */

namespace backend\controllers;


use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class AuthController extends Controller
{
    //添加权限
    public function actionAddPermission(){
        $auth = \Yii::$app->authManager;
        $model = new PermissionForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->description;
                $auth->add($permission);
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index-permission');
            }
        }
        return $this->render('add-permission',['model'=>$model]);
    }
    //权限列表
    public function actionIndexPermission(){
        $auth = \Yii::$app->authManager;
        $permission = $auth->getPermissions();
        return $this->render('index-permission',['permission'=>$permission]);
    }
    //修改权限
    public function actionEditPermission($name){
        $auth = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        $permission = $auth->getPermission($name);
        $model = new PermissionForm();
        $model->name = $permission->name;
        $model->description = $permission->description;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permission->name = $model->name;
                $permission->description = $model->description;
                $auth->update($name,$permission);
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index-permission');
            }
        }
        return $this->render('add-permission',['model'=>$model]);

    }
    //删除权限
    public function actionDelPermission(){
        $name = \Yii::$app->request->post('name');
        $auth = \Yii::$app->authManager;
        $permission = $auth->getPermission($name);
        if ($auth->remove($permission)){
            echo 1;
        }else{
            echo "删除失败";
        }
    }
    //添加角色
    public function actionAddRole(){
        //显示表单
        $auth = \Yii::$app->authManager;
        $model = new RoleForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $role = $auth->createRole($model->name);//创建角色
                $role->description = $model->description;
                $auth->add($role);//添加角色
                foreach ($model->permissions as $permissionName){
                  $permission = $auth->getPermission($permissionName);
                  $auth->addChild($role,$permission);
                }
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index-role');
            }else{
                var_dump($model->getErrors());
            }
        }
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions,'name','description');
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);

    }
    //角色列表
    public function actionIndexRole(){
        $auth = \Yii::$app->authManager;
        $role = $auth->getRoles();
        return $this->render('index-role',['role'=>$role]);
    }
    //修改角色
    public function actionEditRole($name){
        $auth = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        $model = new RoleForm();
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions,'name','description');
        $role = $auth->getRole($name);
        $model->name = $role->name;
        $model->description = $role->description;
        $pers = $auth->getPermissionsByRole($name);
        foreach ($pers as $v){
            $per[] = $v->name;
        }
        $model->permissions = $per;
        if ($request->isPost){
            $model->load($request->post());
            if ($model->validate()){
                $auth->remove($role);//删除旧角色
                $role = $auth->createRole($model->name);//创建角色
                $role->description = $model->description;
                $auth->add($role);//添加角色
                foreach ($model->permissions as $permissionName){
                    $permission = $auth->getPermission($permissionName);
                    $auth->addChild($role,$permission);
                }
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect('index-role');
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);

    }
    //删除角色
    public function actionDelRole(){
        $name = \Yii::$app->request->post('name');
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($auth->remove($role)){
            echo 1;
        }else{
            echo "删除失败";
        }
    }
}
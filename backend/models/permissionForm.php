<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model {
    public $name;
    public $description;
    public $oldName;
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_ADD= 'add';
    public function rules(){
        return [
            [['name','description'],'required'],
            ['name','update','on'=>[self::SCENARIO_UPDATE]],
            ['name','validateAdd','on'=>[self::SCENARIO_ADD]],
        ];
    }
    public function update(){
        $auth = \Yii::$app->authManager;
        if($this->oldName != $this->name){
            $model = $auth->getPermission($this->name);
            if ($model){
                $this->addError('name','权限已存在');
            }
        }
    }
    public function validateAdd(){
       $auth = \Yii::$app->authManager;
       $model = $auth->getPermission($this->name);
       if($model){
           $this->addError('name','权限已经存在');
       }
    }
    public function add(){
        $auth = \Yii::$app->authManager;
        $permission = $auth->createPermission($this->name);
        $permission->description = $this->description;
        return $auth->add($permission);
    }
    public function attributeLabels(){
        return [
            'name'=>"名字(路由)",
            'description'=>"描述",
        ];
    }
}
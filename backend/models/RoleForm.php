<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/9
 * Time: 15:11
 */

namespace backend\models;


use yii\base\Model;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions;
    public $oldName;
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_ADD= 'add';

    public function rules()
    {
        return [
            [['name','description','permissions'],'required'],
            ['name','update','on'=>[self::SCENARIO_UPDATE]],
            ['name','validateAdd','on'=>[self::SCENARIO_ADD]],
        ];
    }
    public function validateAdd(){
        $auth = \Yii::$app->authManager;
        $model = $auth->getRole($this->name);
        if($model){
            $this->addError('name','角色已存在');
        }
    }
    public function add(){
        $auth = \Yii::$app->authManager;
        $role = $auth->createRole($this->name);//创建角色
        $role->description = $this->description;
        $result = $auth->add($role);//添加角色
        foreach ($this->permissions as $permissionName){
            $permission = $auth->getPermission($permissionName);
            $auth->addChild($role,$permission);
        }
        return $result;
    }
    public function update(){
        $auth = \Yii::$app->authManager;
        if($this->oldName != $this->name){
            $model = $auth->getRole($this->name);
            if ($model){
                $this->addError('name','角色已存在');
            }
        }
    }
    public function attributeLabels(){
        return [
            'name'=>"名称",
            'description'=>"描述",
            'permissions'=>"权限",
        ];
    }
}
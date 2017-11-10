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

    public function rules()
    {
        return [
            [['name','description','permissions'],'required'],
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>"名称",
            'description'=>"描述",
            'permissions'=>"权限",
        ];
    }
}
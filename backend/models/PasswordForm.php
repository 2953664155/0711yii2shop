<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/8
 * Time: 19:07
 */

namespace backend\models;



use yii\base\Model;

class PasswordForm extends Model
{
        public $old_pwd;
        public $new_pwd;
        public $confirm_pwd;
        public function rules(){
            return [
                [['old_pwd','new_pwd','confirm_pwd'],'required'],
                ['confirm_pwd', 'compare', 'compareAttribute'=>'new_pwd','message'=>"确认密码必须与新密码一致"],
            ];
        }
        public function attributeLabels(){
            return [
                'old_pwd'=>"旧密码",
                'new_pwd'=>"新密码",
                'confirm_pwd'=>"确认新密码",

            ];
        }
}
<?php

namespace frontend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Member extends ActiveRecord implements IdentityInterface
{
    public function rules()
    {
        return [
            ['username','required','message'=>'用户名不能为空'],
            ['password_hash','required','message'=>'密码不能为空'],
            ['tel','required','message'=>'电话不能为空'],
            ['email','required','message'=>'邮箱不能为空'],
            ['username','CheckName'],
            ['email','CheckEmail'],
            ['tel','CheckTel'],
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key==$authKey;
    }
    //验证用户名唯一性
    public function CheckName(){
        $model= Member::findOne(['username'=>$this->username]);
        if($model){
            return $this->addError('username','用户已存在');
        }
    }
    //验证邮箱唯一性
    public function CheckEmail(){
        $model= Member::findOne(['email'=>$this->email]);
        if($model){
            $this->addError('email','邮箱已存在');
            return $model= Member::findOne(['email'=>$this->email]);
        }
    }
    //验证电话唯一性
    public function CheckTel(){
        $model = Member::findOne(['tel'=>$this->tel]);
        if($model){
            return $this->addError('tel','电话已存在');
        }
    }
}
<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $route
 * @property integer $parent_id
 * @property integer $sort
 * @property integer $tier
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['name', 'route'], 'string', 'max' => 255],
            ['name','unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'route' => '路由',
            'parent_id' => '上级菜单',
            'sort' => '排序',
            'tier' => '层级',
        ];
    }
    public static function getParent(){
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        return ArrayHelper::map($permissions,'name','name');
    }
    public  function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}

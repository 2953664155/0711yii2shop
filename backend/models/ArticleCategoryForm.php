<?php

namespace backend\models;

use yii\base\Model;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $sort
 * @property string $status
 */
class ArticleCategoryForm extends Model
{
    public $name;
    public $intro;
    public $status;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [[ 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name','intro','status'],'required'],
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
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}

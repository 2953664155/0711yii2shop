<?php

namespace backend\models;

use yii\base\Model;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class ArticleForm extends Model
{
    public $name;
    public $intro;
    public $article_category_id;
    public $status;
    public $content;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['article_category_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name','intro','article_category_id','content'], 'required'],
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
            'article_category_id' => '分类',
            'status' => '状态',
            'content' => '内容',
        ];
    }

}

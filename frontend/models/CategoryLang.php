<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "category_lang".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property integer $category_id
 * @property string $title
 * @property string $description
 * @property string $content
 *
 * @property Lang $lang
 */
class CategoryLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'category_id', 'title'], 'required'],
            [['lang_id', 'category_id', 'published'], 'integer'],
            [['content'], 'string'],
            [['title', 'description'], 'string', 'max' => 255],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::className(), 'targetAttribute' => ['lang_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lang_id' => 'Lang ID',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'published' => 'Published',
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }
}

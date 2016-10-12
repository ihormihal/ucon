<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_accommodation_lang".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property integer $object_id
 * @property string $title
 * @property string $description
 * @property string $content
 */
class CatalogAccommodationLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_accommodation_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'object_id', 'title', 'description', 'content'], 'required'],
            [['lang_id', 'object_id', 'active'], 'integer'],
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
            'object_id' => 'Object ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'active' => 'Active',
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

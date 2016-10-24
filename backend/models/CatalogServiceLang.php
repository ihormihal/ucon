<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_service_lang".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property integer $object_id
 * @property string $title
 * @property string $description
 *
 * @property CatalogService $catalogService
 */
class CatalogServiceLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_service_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'object_id', 'title'], 'required'],
            [['lang_id', 'object_id'], 'integer'],
            [['title', 'description'], 'string'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogService()
    {
        return $this->hasOne(CatalogService::className(), ['id' => 'object_id']);
    }
}

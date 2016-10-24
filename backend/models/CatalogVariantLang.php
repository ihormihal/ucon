<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_variant_lang".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property integer $object_id
 * @property string $description
 */
class CatalogVariantLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_variant_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'object_id'], 'required'],
            [['lang_id', 'object_id'], 'integer'],
            [['description'], 'string'],
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
            'description' => 'Description',
        ];
    }
}

<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "catalog_attributes_values".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $object_id
 * @property string $value
 */
class CatalogAttributesValues extends \yii\db\ActiveRecord
{

    public $config;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attributes_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'object_id', 'value'], 'required'],
            [['attribute_id', 'object_id'], 'integer'],
            [['value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attribute_id' => 'Attribute ID',
            'object_id' => 'Object ID',
            'value' => 'Value',
        ];
    }

    public function getAttr()
    {
        return $this->hasOne(CatalogAttributes::className(), ['id' => 'attribute_id']);
    }
}

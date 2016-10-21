<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "price_variant".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $model_name
 * @property string $attributes
 * @property double $price
 * @property integer $published
 */
class PriceVariant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'model_name', 'attributes', 'price'], 'required'],
            [['object_id', 'published'], 'integer'],
            [['attributes'], 'string'],
            [['price'], 'number'],
            [['model_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'model_name' => 'Model Name',
            'attributes' => 'Attributes',
            'price' => 'Price',
            'published' => 'Published',
        ];
    }
}

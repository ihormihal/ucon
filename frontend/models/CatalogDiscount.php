<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "catalog_discount".
 *
 * @property integer $id
 * @property integer $object_id
 * @property string $model_name
 * @property double $discount
 * @property string $period_from
 * @property string $period_to
 */
class CatalogDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'model_name', 'period_from', 'period_to'], 'required'],
            [['object_id'], 'integer'],
            [['discount'], 'number'],
            [['period_from', 'period_to'], 'safe'],
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
            'discount' => 'Discount',
            'period_from' => 'Period From',
            'period_to' => 'Period To',
        ];
    }
}

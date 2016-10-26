<?php

namespace frontend\models;

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
class CatalogVariant extends \yii\db\ActiveRecord
{

    public $lang_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_variant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_id', 'model_name', 'price'], 'required'],
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

    //get current language content for VARIANT
    public function getContent()
    {
        $lang_id = ($this->lang_id) ? $this->lang_id : Lang::getCurrent();
        return $this->hasOne(CatalogVariantLang::className(), ['object_id' => 'id'])->where(['lang_id' => $lang_id]);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

}

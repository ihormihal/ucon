<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_service".
 *
 * @property integer $id
 * @property double $price
 * @property integer $published
 *
 * @property CatalogServiceLang $id0
 */
class CatalogService extends \yii\db\ActiveRecord
{
    public $lang_id;
    public $image;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['accommodation_id', 'published'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accommodation_id' => 'Accommodation ID',
            'price' => 'Price',
            'published' => 'Published',
        ];
    }

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    //get parent accommodation for SERVICE
    public function getAccommodation()
    {
        return $this->hasOne(CatalogAccommodation::className(), ['id' => 'accommodation_id']);
    }

    //get current language content for ROOM
    public function getContent()
    {
        $lang_id = ($this->lang_id) ? $this->lang_id : Lang::getCurrent();
        return $this->hasOne(CatalogServiceLang::className(), ['object_id' => 'id'])->where(['lang_id' => $lang_id]);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}

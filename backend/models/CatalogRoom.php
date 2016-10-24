<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_room".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $published
 */
class CatalogRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $image;

    public static function tableName()
    {
        return 'catalog_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['accommodation_id','published'], 'integer'],
            [['alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'accommodation_id' => 'Accomnodation ID',
            'alias' => 'Alias',
            'published' => 'Published',
            'contents' => 'Contents',
            'attrs' => 'Attributes'
        ];
    }

    //image

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    //get parent accommodation for ROOM
    public function getAccommodation()
    {
        return $this->hasOne(CatalogAccommodation::className(), ['id' => 'accommodation_id']);
    }

    //get ROOM title for current language
    public function getTitle($lang_id = null)
    {
        $lang_id = ($lang_id === null) ? Lang::getCurrent() : (int)$lang_id;
        $content = CatalogRoomLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
        if($content){
            return $content->title;
        }else{
            return $this->id;
        }
    }

    //get current language content for ROOM
    public function getContent($lang_id = null)
    {
        $lang_id = ($lang_id === null) ? Lang::getCurrent() : (int)$lang_id;
        return CatalogRoomLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
    }

    //get languages content for ROOM
    public function getContents()
    {
        return $this->hasMany(CatalogRoomLang::className(), ['object_id' => 'id']);
    }

    //get season discounts for ROOM
    public function getDiscounts()
    {
        return $this->hasMany(CatalogDiscount::className(), ['object_id' => 'id'])->where(['model_name' => 'CatalogRoom']);
    }

    //get discount for date
    // public function getDiscount($date = null){
    //     if($date === null) $date = date('d-m-Y');

    //     $a_discount = $this->accommodation->getDiscount();
    //     $r_discount = CatalogDiscount::findOne([
    //         'object_id' => $this->id, 
    //         'model_name' => 'CatalogRoom'
    //     ])->where('period_from < :date AND period_to > :date', [':period_from' => $date]);
    //     return $r_discount || $a_discount;
    // }

    //get all attributes for ROOM and fill it by values
    public function getAttrs()
    {
        $attributes = [];
        $attrs = CatalogAttribute::find()->where(['model_name' => 'CatalogRoom'])->all();
        //get values
        foreach($attrs as $attr){
            $values = CatalogAttributeValue::findOne(['attribute_id' => $attr->id, 'object_id' => $this->id]);
            if($values === null){
                $values = new CatalogAttributeValue(['attribute_id' => $attr->id, 'object_id' => $this->id]);
            }
            $values->config = $attr;
            $attributes[$attr->alias] = $values;
        }
        return $attributes;
    }
}

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

    public function getAccommodation()
    {
        return $this->hasOne(CatalogAccommodation::className(), ['id' => 'accommodation_id']);
    }

    //langs

    protected function getDefaultLang(){
        if (($model = Lang::find()->where(['default' => 1, 'published' => 1])->one()) !== null) {
            return $model->id;
        } else {
            return 1;
        }
    }

    public function getTitle($lang_id = null)
    {
        $lang_id = ($lang_id === null) ? $this->getDefaultLang() : (int)$lang_id;
        $content = CatalogRoomLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
        if($content){
            return $content->title;
        }else{
            return $this->id;
        }
    }

    public function getContent($lang_id = null)
    {
        $lang_id = ($lang_id === null) ? $this->getDefaultLang() : (int)$lang_id;
        return CatalogRoomLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
    }

    public function getContents()
    {
        return $this->hasMany(CatalogRoomLang::className(), ['object_id' => 'id']);
    }

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

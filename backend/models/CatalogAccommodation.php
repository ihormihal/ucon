<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_accommodation".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $author
 * @property integer $active
 */
class CatalogAccommodation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $image;

    public static function tableName()
    {
        return 'catalog_accommodation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'author'], 'required'],
            [['author', 'active'], 'integer'],
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
            'alias' => 'Alias',
            'author' => 'Author',
            'active' => 'Active',
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

    //langs

    public function getTitle($lang_id = null)
    {
        $lang_id = ($lang_id === null) ? 2 : (int)$lang_id;
        $content = CatalogAccommodationLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
        if($content){
            return $content->title;
        }else{
            return $this->id;
        }
    }

    public function getContent($lang_id = null)
    {
        //$lang_id = ($lang_id === null)? Lang::getCurrent()->id: $lang_id;
        $lang_id = ($lang_id === null) ? 1 : (int)$lang_id;
        return CatalogAccommodationLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
    }

    public function getContents()
    {
        return $this->hasMany(CatalogAccommodationLang::className(), ['object_id' => 'id']);
    }

    public function getAttrs()
    {
        $attributes = [];
        $attrs = CatalogAttributes::find()->where(['model_name' => 'CatalogAccommodation'])->all();
        //get values
        foreach($attrs as $attr){
            $values = CatalogAttributesValues::findOne(['attribute_id' => $attr->id, 'object_id' => $this->id]);
            if($values === null){
                $values = new CatalogAttributesValues(['attribute_id' => $attr->id, 'object_id' => $this->id]);
            }
            $values->config = $attr;
            $attributes[$attr->alias] = $values;
        }
        return $attributes;
    }
}

<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_accommodation".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $author
 * @property integer $published
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
            [['author', 'published'], 'integer'],
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
        $content = CatalogAccommodationLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
        if($content){
            return $content->title;
        }else{
            return $this->id;
        }
    }

    public function getContent($lang_id = null)
    {
        $lang_id = ($lang_id === null) ? $this->getDefaultLang() : (int)$lang_id;
        return CatalogAccommodationLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
    }

    public function getContents()
    {
        return $this->hasMany(CatalogAccommodationLang::className(), ['object_id' => 'id']);
    }

    public function getAttrs()
    {
        $attributes = [];
        $attrs = CatalogAttribute::find()->where(['model_name' => 'CatalogAccommodation'])->all();
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

    // public function getAttrs()
    // {

    //     //$attrs = $this->hasMany(CatalogAttributeValues::className(), ['object_id' => 'id'])->innerJoinWith('attr AS a')->where(['a.model_name' => 'CatalogAccommodation']);
    //     $attrs = CatalogAttributeValues::find()->alias('v')->joinWith('attr AS a')->where(['v.object_id' => $this->id, 'a.model_name' => 'CatalogAccommodation'])->asArray()->all();
    //     $attributes = [];
    //     foreach ($attrs as $attr) {
    //         $attributes[$attr['attr']['alias']] = htmlentities($attr['value'], ENT_QUOTES, 'UTF-8');
    //     }
    //     return $attributes;
    // }
}

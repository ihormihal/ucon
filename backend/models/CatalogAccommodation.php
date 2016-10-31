<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_accommodation".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $author
 * @property integer $stars
 * @property integer $published
 */
class CatalogAccommodation extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */

	public $image;
    public $lang_id;

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
			[['author', 'stars', 'published'], 'integer'],
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
			'stars' => 'Stars',
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

	//get ACCOMMODATION title for current language
	public function getTitle()
	{
        $lang_id = ($this->lang_id) ? $this->lang_id : Lang::getCurrent();
		$content = CatalogAccommodationLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
		if($content){
			return $content->title;
		}else{
			return 'Отель #'.$this->id;
		}
	}

    //get current language content for ACCOMMODATION
    public function getContent()
    {
        $lang_id = ($this->lang_id) ? $this->lang_id : Lang::getCurrent();
        return $this->hasOne(CatalogAccommodationLang::className(), ['object_id' => 'id'])->where(['lang_id' => $lang_id]);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

	//get languages content for ACCOMMODATION
	public function getContents()
	{
		return $this->hasMany(CatalogAccommodationLang::className(), ['object_id' => 'id']);
	}

	//get season discounts for ACCOMMODATION
	public function getDiscounts()
	{
		return $this->hasMany(CatalogDiscount::className(), ['object_id' => 'id'])->where(['model_name' => 'CatalogAccommodation']);
	}

	//get current season discount for ACCOMMODATION
	public function getDiscount($date = null)
	{
		if($date === null) $date = date('Y-m-d');

		// return CatalogDiscount::find()
		// ->where(['model_name' => 'CatalogAccommodation', 'object_id' => $this->id])
		// ->andWhere(['<=', 'period_from', $date])
		// ->andWhere(['>=', 'period_to', $date])->all();

		return $this->hasOne(CatalogDiscount::className(), ['object_id' => 'id'])
		->where(['model_name' => 'CatalogAccommodation'])
		->andWhere(['<=', 'period_from', $date])
		->andWhere(['>=', 'period_to', $date]);
	}

	//get all attributes for ACCOMMODATION and fill it by values
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
}

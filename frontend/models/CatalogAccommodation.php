<?php

namespace frontend\models;

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

	//langs
	public function getContent($lang_id = null)
	{
		$lang_id = ($lang_id === null)? Lang::getCurrent()->id: $lang_id;
		return $this->hasOne(CatalogAccommodationLang::className(), ['object_id' => 'id'])->where(['lang_id' => $lang_id, 'published' => 1]);
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

	public function getAttrs()
	{

		$attrs = CatalogAttributeValue::find()->alias('v')->joinWith('attr AS a')->where(['v.object_id' => $this->id, 'a.model_name' => 'CatalogAccommodation'])->asArray()->all();
		$attributes = [];
		foreach ($attrs as $attr) {
			$attributes[$attr['attr']['alias']] = htmlentities($attr['value'], ENT_QUOTES, 'UTF-8');
		}
		return $attributes;
	}
}

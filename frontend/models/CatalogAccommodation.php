<?php

namespace frontend\models;

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
	public function getContent($lang_id = null)
	{
		$lang_id = ($lang_id === null)? Lang::getCurrent()->id: $lang_id;
		return $this->hasOne(CatalogAccommodationLang::className(), ['object_id' => 'id'])->where(['lang_id' => $lang_id, 'published' => 1]);
	}

	//attr
	// public function getAttributeValue($alias = null)
	// {	
	// 	if($alias === null){
	// 		return null;
	// 	}

	// 	$location = CatalogAttributesValues::find()
	// 		->alias('v')
	// 		->innerJoinWith('attr AS a')
	// 		->where(['v.object_id' => $this->id, 'a.model_name' => 'CatalogAccommodation', 'a.alias' => strval($alias)])
	// 		->one();

	// 	if($location){
	// 		return htmlentities($location->value, ENT_QUOTES, 'UTF-8'); //to json
	// 	}
	// }

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

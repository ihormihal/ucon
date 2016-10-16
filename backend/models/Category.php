<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $alias
 * @property integer $published
 */
class Category extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */

	public $image;

	public static function tableName()
	{
		return 'category';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['alias'], 'required'],
			[['published'], 'integer'],
			[['alias', 'model_name'], 'string', 'max' => 255],
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
			'model_name' => 'Model name',
			'published' => 'Published',
			'contents' => 'Contents'
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
		$content = CategoryLang::findOne(['category_id' => $this->id, 'lang_id' => $lang_id]);
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
		return CategoryLang::findOne(['category_id' => $this->id, 'lang_id' => $lang_id]);
	}

	public function getContents()
	{
		return $this->hasMany(CategoryLang::className(), ['category_id' => 'id']);
	}
}

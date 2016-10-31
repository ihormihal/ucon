<?php

namespace frontend\models;

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
	public function getContent($lang_id = null)
    {
        $lang_id = ($lang_id === null)? Lang::getCurrent()->id: $lang_id;
        return $this->hasOne(CategoryLang::className(), ['object_id' => 'id'])->where('lang_id = :lang_id', [':lang_id' => $lang_id]);
    }
}

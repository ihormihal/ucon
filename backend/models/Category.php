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
    public $lang_id;

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


	public function getTitle()
	{
        $lang_id = ($this->lang_id) ? $this->lang_id : Lang::getCurrent();
		$content = CategoryLang::findOne(['object_id' => $this->id, 'lang_id' => $lang_id]);
		if($content){
			return $content->title;
		}else{
			return 'Category #'.$this->id;
		}
	}

    public function getContent()
    {
        $lang_id = ($this->lang_id) ? $this->lang_id : Lang::getCurrent();
        return $this->hasOne(CategoryLang::className(), ['object_id' => 'id'])->where(['lang_id' => $lang_id]);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

	public function getContents()
	{
		return $this->hasMany(CategoryLang::className(), ['category_id' => 'id']);
	}
}

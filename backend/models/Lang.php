<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $url
 * @property string $locale
 * @property string $name
 * @property integer $published
 * @property integer $default
 */
class Lang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'locale', 'name'], 'required'],
            [['published', 'default'], 'integer'],
            [['url', 'locale', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'locale' => 'Locale',
            'name' => 'Name',
            'published' => 'Published',
            'default' => 'Default',
        ];
    }
}

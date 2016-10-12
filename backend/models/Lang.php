<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $lng
 * @property string $locale
 * @property string $name
 * @property integer $active
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
            [['lng', 'locale', 'name'], 'required'],
            [['active', 'default'], 'integer'],
            [['lng', 'locale', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lng' => 'Lng',
            'locale' => 'Locale',
            'name' => 'Name',
            'active' => 'Active',
            'default' => 'Default',
        ];
    }
}

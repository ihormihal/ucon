<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_attributes".
 *
 * @property integer $id
 * @property string $model_name
 * @property string $alias
 * @property string $name
 * @property string $config
 */
class CatalogAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'catalog_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'model_name', 'name', 'type'], 'required'],
            [['model_name', 'type', 'values_model_name', 'values_config', 'values'], 'string'],
            [['alias', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_name' => 'Model Name',
            'alias' => 'Alias',
            'name' => 'Name',
            'type' => 'Type',
            'values_model_name' => 'Values Model Name',
            'values_config' => 'Values',
            'values' => 'Values'
        ];
    }

    public function getValues(){
        return htmlentities($this->values_config, ENT_QUOTES, 'UTF-8');
    }

}

<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "catalog_room_lang".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property integer $room_id
 * @property string $title
 * @property string $description
 * @property string $content
 */
class CatalogRoomLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_room_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'object_id', 'title'], 'required'],
            [['lang_id', 'object_id', 'published'], 'integer'],
            [['content'], 'string'],
            [['title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lang_id' => 'Lang ID',
            'object_id' => 'Room ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'published' => 'Published'
        ];
    }
}

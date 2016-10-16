<?php
namespace frontend\widgets;
use frontend\models\CatalogRooms;

class PopularRooms extends \yii\bootstrap\Widget
{
	public function init(){}

	public function run() {
		return $this->render('popular/rooms', [
			'rooms' => CatalogRooms::find()->alias('r')->innerJoinWith('accommodation AS a')->where(['r.published' => 1, 'a.published' => 1])->all(),
		]);
	}
}
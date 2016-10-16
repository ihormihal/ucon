<?php
namespace frontend\widgets;
use frontend\models\CatalogAccommodation;

class PopularHotels extends \yii\bootstrap\Widget
{
	public function init(){}

	public function run() {
		return $this->render('popular/hotels', [
			'hotels' => CatalogAccommodation::find()->where(['published' => 1])->all(),
		]);
	}
}
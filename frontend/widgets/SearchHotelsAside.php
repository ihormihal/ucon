<?php
namespace frontend\widgets;

class SearchHotelsAside extends \yii\bootstrap\Widget
{
	public function init(){}

	public function run() {
		return $this->render('search/aside_hotels', []);
	}
}
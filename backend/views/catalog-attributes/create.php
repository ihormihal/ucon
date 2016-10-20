<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Новый Атрибут';
$this->params['breadcrumbs'][] = $category->getTitle();
//$this->params['breadcrumbs'][] = ['label' => $category->getTitle(), 'url' => ['category/view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['index', 'model_name' => $category->model_name]];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="pt1 pb1 orange-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> новый атрибут
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">
		<h1><?= Html::encode($this->title) ?></h1>

		<?= $this->render('_form', [
			'model' => $model,
			'model_name' => $category->model_name,
		]) ?>

	</div>
</section>
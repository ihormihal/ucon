<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->name;
$this->params['breadcrumbs'][] = $category->getTitle();
//$this->params['breadcrumbs'][] = ['label' => $category->getTitle(), 'url' => ['category/view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['index', 'model_name' => $category->model_name]];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="pt1 pb1 <?= $status ?>-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> 
		<?php
		if($status == 'edit'){
			echo 'редактирование';
		}elseif($status == 'success'){
			echo 'успешно обновлено';
		}elseif($status == 'error'){
			echo 'ошибка сохранения';
		}
		?>
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
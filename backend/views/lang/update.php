<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lang */

$this->title = 'Update Lang: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>

<section class="pt1 pb1 green-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> редактирование языка
	</div>
</section>

<section class="pb1">
	<div class="container wide">

		<h1 class="mt1 mb1"><?= Html::encode($model->name) ?></h1>

		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</section>
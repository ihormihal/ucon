<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lang */

$this->title = 'Create Lang';
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="pt1 pb1 orange-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> добавление языка
	</div>
</section>

<section class="pb1">
	<div class="container wide">

		<h1 class="mt1 mb1">Новый язык</h1>

		<div class="mb2"></div>
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</section>

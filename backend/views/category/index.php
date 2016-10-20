<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="pt1 pb1 orange-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> Новый документ
	</div>
</section>

<section>
	<div class="container wide">
		<h1><?= Html::encode($this->title) ?></h1>
		<?php $this->render('_search', ['model' => $searchModel]); ?>
	
	
		<?= Html::a('Create Category', ['create'], ['class' => 'btn btn-mt btn-success']) ?>
		
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'columns' => [
				['class' => 'yii\grid\SerialColumn'],

				'id',
				'alias',
				'published',

				['class' => 'yii\grid\ActionColumn'],
			],
		]); ?>
	</div>
</section>

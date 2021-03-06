<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\CustomHelpers;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Языки';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="pt1 pb1 blue-bg">
	<div class="container wide">
		<h1><?= $this->title ?></h1>
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">
		<?= Html::a('<i class="fa fa-plus"></i> Добавить язык', ['create'], ['class' => 'btn btn-primary ripple']) ?>
	</div>
</section>

<section class="pb1">
	<div class="container wide">
		<table class="datatable clickable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Alias</th>
					<th>Locale</th>
					<th>Name</th>
					<th>Active</th>
					<th>Default</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($collection as $key => $item): ?>
				<tr data-href="<?= Url::toRoute(['update', 'id' => $item->id]) ?>">
					<td><?= $item->id ?></td>
					<td><?= $item->url ?></td>
					<td><?= $item->locale ?></td>
					<td><?= $item->name ?></td>
					<td><?= $item->published ?></td>
					<td><?= $item->default ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</section>

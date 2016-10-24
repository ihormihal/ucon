<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->getTitle();
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="pt1 pb1 main-bg">
	<div class="container wide">
		<h1><?= $this->title; ?></h1>
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">
		<div class="btn-group">
			<?= Html::a('<i class="fa fa-plus"></i> Добавить отель', ['create'], ['class' => 'btn btn-primary ripple']) ?>
			<?= Html::a('<i class="fa fa-pencil"></i> Обновить категорию', ['category/update', 'id' => $category->id, 'lang_id' => $lang->id], ['class' => 'btn btn-default ripple']) ?>
			<?= Html::a('<i class="fa fa-pencil"></i> Атрибуты', ['catalog-attribute/index', 'model_name' => $category->model_name], ['class' => 'btn btn-default ripple']) ?>
		</div>
	</div>
</section>

<section class="pb1">
	<div class="container wide">
		<div im-datatable>
			<div class="row">
				<div class="col-md-4">
					<input type="text" class="default full" ng-model="s" placeholder="Search..." />
				</div>
			</div>
			<table class="datatable">
				<thead>
					<tr>
						<th data-type="numeric">ID</th>
						<th>Alias</th>
						<th data-type="numeric">Author</th>
						<th data-type="numeric">Published</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($collection as $key => $item): ?>
					<tr data-href="<?= Url::toRoute(['update', 'id' => $item->id, 'lang_id' => $lang->id]) ?>">
						<td><?= $item->id ?></td>
						<td><?= $item->alias ?></td>
						<td><?= $item->author ?></td>
						<td><?= $item->published ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
				<!-- <tfoot>
					<th><input ng-model="search.cells.key0.text" class="full" type="text" placeholder="ID"></th>
					<th><input ng-model="search.cells.key1.text" class="full" type="text" placeholder="Alias"></th>
					<th><input ng-model="search.cells.key2.text" class="full" type="text" placeholder="Author"></th>
					<th></th>
				</tfoot> -->
			</table>

			<div class="row">
				<div class="col-md-10">
					<div im-datatable-pagination></div>
				</div>
				<div class="col-md-2">
					<select class="full default" ng-model="table.onpage">
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</div>
			</div>

		</div>
	</div>
</section>

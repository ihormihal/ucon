<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="pt1 pb1 blue-bg">
	<div class="container wide">
		<h1><?= $this->title ?></h1>
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">
		<?= Html::a('<i class="fa fa-plus"></i> Добавить пользователя', ['create'], ['class' => 'btn btn-primary ripple']) ?>
	</div>
</section>

<section class="pb1">
	<div class="container wide">
		<table class="datatable clickable">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Email</th>
					<th>Role</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($collection as $key => $item): ?>
				<tr data-href="<?= Url::toRoute(['update', 'id' => $item->id]) ?>">
					<td><?= $item->id ?></td>
					<td><?= $item->username ?></td>
					<td><?= $item->email ?></td>
					<td><?= $item->role ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</section>
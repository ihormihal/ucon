<?php

use yii\helpers\Html;
use yii\helpers\CustomHelpers;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$this->title = $model->getTitle();
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-aside dark-bg">
    <?= $this->render('/site/nav/aside'); ?>
</div>
<div class="col-main">
    <section class="pt1 pb1 white-bg">
        <div class="container wide">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </div>
    </section>

    <section class="pt1 pb1 blue-bg">
        <div class="container wide">
            <span class="thin">Статус:</span> просмотр категории
        </div>
    </section>

    <section class="pt1 pb1">
    	<div class="container wide">
    		<?= Html::a('<i class="fa fa-pencil"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-mt btn-primary']) ?>
			<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
				'class' => 'btn btn-mt btn-danger',
				'data' => [
					'confirm' => 'Are you sure you want to delete this item?',
					'method' => 'post',
				],
			]) ?>
    	</div>
    </section>

    <section class="pb1">
    	<div class="container wide">
	    	<div>
				<div class="row">
					<div class="col-md-4">
						<input type="text" class="full" ng-model="s" placeholder="Search..." />
					</div>
				</div>
				<table class="full striped hoverable clickable noborder ng-table">
					<thead>
						<tr>
							<th>Alias</th>
							<th>Active</th>
						</tr>
					</thead>
					<tbody>
						<!-- repeat -->
						<tr data-href="#">
							<td>Sanatorium</td>
							<td>
								<div class="switch">
									<label>
										<input type="checkbox" value="1" checked="">
										<span class="toggle"></span>
									</label>
								</div>
							</td>
						</tr>
						<tr data-href="#">
							<td>Sanatorium</td>
							<td>
								<div class="switch">
									<label>
										<input type="checkbox" value="1">
										<span class="toggle"></span>
									</label>
								</div>
							</td>
						</tr>
						<!-- /repeat -->
					</tbody>
					<tfoot>
						<th></th>
						<th></th>
					</tfoot>
				</table>

				<div class="row">
					<div class="col-md-10">
						<div im-datatable-pagination></div>
					</div>
					<div class="col-md-2">
						<select class="full" ng-model="table.onpage">
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
</div>




<!-- 	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'alias',
			'published',
		],
	]) ?> -->

<!-- 
	<?php foreach ($model->contents as $key => $content): ?>
		<h2><?= CustomHelpers::findObject($languages, 'id', $content->lang_id)->name ?></h2>
		<?= DetailView::widget([
			'model' => $content,
			'attributes' => [
				'id',
				'lang_id',
				'title',
				'description',
				'content'
			],
		]) ?>
	<?php endforeach ?>
 -->


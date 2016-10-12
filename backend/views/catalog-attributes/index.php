<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = 'Атрибуты';
$this->params['breadcrumbs'][] = $category->getTitle();
//$this->params['breadcrumbs'][] = ['label' => $category->getTitle(), 'url' => ['category/view', 'id' => $category->id]];
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
            <h1><?= $this->title ?> (<?= $category->getTitle() ?>)</h1>
        </div>
    </section>

    <section class="pt1 pb1">
    	<div class="container wide">
    		<div class="btn-group">
	            <?= Html::a('<i class="fa fa-plus"></i> Add Object', ['create', 'model_name' => $category->model_name], ['class' => 'btn btn-mt btn-success']) ?>
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
                            <th>Name</th>
                            <th>Alias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collection as $key => $item): ?>
                        <tr data-href="<?= Url::toRoute(['update', 'id' => $item->id, 'model_name' => $category->model_name]) ?>">
                            <td><?= $item->id ?></td>
                            <td><?= $item->name ?></td>
                            <td><?= $item->alias ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
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

</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->getTitle();
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
            <h1><?= $this->title; ?></h1>
        </div>
    </section>

    <section class="pt1 pb1">
        <div class="container wide">
            <div class="btn-group">
                <?= Html::a('<i class="fa fa-pencil"></i> Attributes', ['catalog-attributes/index', 'model_name' => $category->model_name], ['class' => 'btn btn-mt btn-default']) ?>
                <?= Html::a('<i class="fa fa-pencil"></i> Update Category', ['category/update', 'id' => $category->id, 'lang_id' => $lang->id], ['class' => 'btn btn-mt btn-primary']) ?>
                <?= Html::a('<i class="fa fa-plus"></i> Add Object', ['create'], ['class' => 'btn btn-mt btn-success']) ?>
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
                            <th data-type="numeric">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collection as $key => $item): ?>
                        <tr data-href="<?= Url::toRoute(['update', 'id' => $item->id, 'lang_id' => $lang->id]) ?>">
                            <td><?= $item->id ?></td>
                            <td><?= $item->alias ?></td>
                            <td><?= $item->author ?></td>
                            <td><?= $item->active ?></td>
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

</div>

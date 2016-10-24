<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="form-group btn-group">
    <?= Html::a('<i class="fa fa-plus"></i> Добавить номер', ['/catalog-room/create', 'accommodation_id' => $accommodation_id], ['class' => 'btn btn-primary ripple']) ?>
</div>

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
                <th data-type="numeric">Published</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($collection as $key => $item): ?>
            <tr data-href="<?= Url::toRoute(['catalog-room/update', 'id' => $item->id, 'lang_id' => $lang_id]) ?>">
                <td><?= $item->id ?></td>
                <td><?= $item->alias ?></td>
                <td><?= $item->published ?></td>
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
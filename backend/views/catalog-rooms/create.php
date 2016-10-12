<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CatalogRooms */

$this->title = 'Create Catalog Rooms';
$this->params['breadcrumbs'][] = ['label' => 'Catalog Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-rooms-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

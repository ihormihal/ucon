<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CatalogRoomsLang */

$this->title = 'Create Catalog Rooms Lang';
$this->params['breadcrumbs'][] = ['label' => 'Catalog Rooms Langs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-rooms-lang-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

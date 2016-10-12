<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model backend\models\CatalogSanatoriums */

$this->title = 'Новый Атрибут';
$this->params['breadcrumbs'][] = ['label' => $category->getTitle(), 'url' => ['category/view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['index', 'model_name' => $category->model_name]];
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

    <section class="pt1 pb1 orange-bg">
        <div class="container wide">
            <span class="thin">Статус:</span> новый атрибут
        </div>
    </section>

    <section class="pt1 pb1">
    	<div class="container wide">
    		<h1><?= Html::encode($this->title) ?></h1>

    		<?= $this->render('_form', [
		        'model' => $model,
                'model_name' => $category->model_name,
		    ]) ?>

    	</div>
    </section>


</div>

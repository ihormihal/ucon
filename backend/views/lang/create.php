<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $model app\models\Lang */

$this->title = 'Create Lang';
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['index']];
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

    <section class="pt1 pb1 green-bg">
        <div class="container wide">
            <span class="thin">Статус:</span> добавление языка
        </div>
    </section>

    <section class="pb1">
        <div class="container wide">
        	<h1><?= Html::encode($model->name) ?></h1>
        	<div class="mb2"></div>
        	<?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
        </div>
    </section>

</div>

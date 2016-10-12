<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\Lang */

$this->title = 'Update Lang: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Языки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
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
            <span class="thin">Статус:</span> редактирование языка
        </div>
    </section>

    <section class="pb1">
        <div class="container wide">

        	<h1 class="mt1 mb1"><?= Html::encode($model->name) ?></h1>

        	<?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
        </div>
    </section>

</div>

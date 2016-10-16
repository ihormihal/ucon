<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $model backend\models\CatalogSanatoriums */

$this->title = 'Create Room';
$this->params['breadcrumbs'][] = ['label' => $accomodation->getTitle(), 'url' => ['catalog-accommodation/update', 'id' => $accomodation->id]];
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
            <span class="thin">Статус:</span> новый номер
        </div>
    </section>

    <section class="pt1 pb1">
    	<div class="container wide">
    		<h1><?= Html::encode($this->title) ?></h1>
    		<?php
				$form = ActiveForm::begin([
				'id' => 'room-create-form',
				'options' => [
					'class' => ''
				]
			]) ?>
			<?= $form->field($model, 'author')->hiddenInput(['value'=> 1])->label(false); ?>

			<div class="form-group">
				<label>Title</label>
				<input class="full default" type="text" ng-model="toalias">
			</div>
			<div class="row">
				<div class="col-md-9">
					<?= $form->field($model, 'alias')->textinput(['ng-model' => 'alias', 'class' => 'full default']) ?>
				</div>
				<div class="col-md-3">

					<?= $form->field($model, 'published', [
						'options' => ['class' => 'form-group no-label'],
						'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
					])->checkbox([],false) ?>

				</div>
			</div>
			<div class="form-group mt1">
				<?= Html::submitButton('<i class="fa fa-arrow-right"></i> Create', ['class' => 'btn btn-mt btn-primary']) ?>
			</div>
			<?php ActiveForm::end() ?>
		</div>
	</section>

</div>
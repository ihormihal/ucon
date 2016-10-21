<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;


$this->title = 'Create Accommodation';
$this->params['breadcrumbs'][] = ['label' => 'Отели', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="pt1 pb1 orange-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> новое жилье
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">
		<h1><?= Html::encode($this->title) ?></h1>
		<?php
			$form = ActiveForm::begin([
			'id' => 'accommodation-create-form',
			'options' => [
				'class' => ''
			]
		]) ?>
		<?= $form->field($model, 'author')->hiddenInput(['value'=> 1])->label(false); ?>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'alias')->textinput(['ng-model' => 'alias', 'class' => 'full default']) ?>
			</div>
			<?php if (Yii::$app->user->can('contentAccess')): ?>
			<div class="col-md-3">
				<?= $form->field($model, 'author')->dropDownList(
					ArrayHelper::map($users,'id','username'), 
					['class' => 'default full']
				) ?>
			</div>
			<?php endif ?>
			<div class="col-md-3">


				<?php if (Yii::$app->user->can('contentAccess')): ?>
				<?= $form->field($model, 'published', [
					'options' => ['class' => 'form-group no-label'],
					'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
				])->checkbox([],false) ?>
				<?php else: ?>
					<div class="form-group no-label">
						<?php if ($model->published): ?>
							<button class="btn btn-flat btn-success" disabled>Published</button>
						<?php else: ?>
							<button class="btn btn-flat btn-warning" disabled>Not Published</button>
						<?php endif ?>
					</div>
				<?php endif ?>

			</div>
		</div>

		<div class="form-group btn-group mt2">
			<?php foreach ($languages as $key => $lang): ?>
				<?php $class = $lang_id == $lang->id ? 'btn-primary' : 'btn-default'; ?>

				<?= Html::a($lang->name, ['create', 'lang_id' => $lang->id], ['class' => 'btn btn-mt '.$class.' ripple']) ?>
			<?php endforeach ?>
		</div>

		<?= $form->field($content, 'lang_id')->hiddenInput()->label(false); ?>
		<div class="row">
			<div class="col-md-9">
				<?= $form->field($content, 'title')->textInput(['class' => 'full default', 'ng-model' => 'toalias']) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($content, 'published', [
					'options' => ['class' => 'form-group no-label'],
					'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
				])->checkbox([],false) ?>
			</div>
		</div>

		<?= $form->field($content, 'description')->textArea(['class' => 'full default', 'rows' => '3']) ?>
		<?= $form->field($content, 'content')->widget(CKEditor::className(), [
			'options' => ['rows' => 6],
			'preset' => 'standart'
		]) ?>

		<div class="form-group btn-group pt1">
			<?= Html::submitButton('<i class="fa fa-check"></i> Create', ['class' => 'btn btn-mt btn-success']) ?>
		</div>

		<?php ActiveForm::end() ?>
	</div>
</section>

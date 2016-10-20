<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;


$this->title = 'Create Room';
$this->params['breadcrumbs'][] = ['label' => $accomodation->getTitle(), 'url' => ['catalog-accommodation/update', 'id' => $accomodation->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


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

		<div class="form-group btn-group border-top border-blue pt1">
			<?= Html::submitButton('<i class="fa fa-check"></i> Create', ['class' => 'btn btn-mt btn-success']) ?>
		</div>
		<?php ActiveForm::end() ?>
	</div>
</section>
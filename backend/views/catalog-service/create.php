<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;


$this->title = 'Новый сервис';
$this->params['breadcrumbs'][] = ['label' => $model->accommodation->getTitle(), 'url' => ['catalog-accommodation/update', 'id' => $model->accommodation->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="pt1 pb1 orange-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> новый сервис
	</div>
</section>

<?php $form = ActiveForm::begin(['id' => 'room-create-form']) ?>

<section class="white-bg">
	<div class="container wide">
		<div class="row tile thin">
			<div class="col-md-8">
				<div class="btn-group">
					<?php foreach ($languages as $key => $lang): ?>
						<?php $class = $model->lang_id == $lang->id ? 'btn-primary' : 'btn-default'; ?>

						<?= Html::a($lang->name, ['create', 'lang_id' => $lang->id, 'accommodation_id' => $model->accommodation->id], ['class' => 'btn '.$class.' ripple']) ?>
					<?php endforeach ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="btn-group text-md-right">
					<?= Html::submitButton('<i class="fa fa-save"></i> Создать', ['class' => 'btn btn-success ripple']) ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">
		<h1 class="mb1"><?= Html::encode($this->title) ?></h1>
		
		
		<?= $form->field($model, 'price')->textInput(['class' => ' default']) ?>

		<?= $form->field($model->content, 'lang_id')->hiddenInput()->label(false); ?>

		<div class="row">
			<div class="col-md-9">
				<?= $form->field($model->content, 'title')->textInput(['class' => 'full default', 'ng-model' => 'toalias']) ?>
			</div>
			<div class="col-md-3">

				<?= $form->field($model, 'published', [
					'options' => ['class' => 'form-group no-label'],
					'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
				])->checkbox([],false) ?>

			</div>
		</div>
		
		<?= $form->field($model->content, 'description')->widget(CKEditor::className(), [
			'options' => ['rows' => 6],
			'preset' => 'standart'
		]) ?>

	</div>
</section>

<?php ActiveForm::end() ?>
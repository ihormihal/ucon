<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

$this->params['breadcrumbs'][] = ['label' => $model->getTitle($model->lang_id), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<section class="pt1 pb1 <?= $status ?>-bg">
	<div class="container wide">
		<span class="thin">Статус:</span>
		<?php
		if($status == 'edit'){
			echo 'редактирование';
		}elseif($status == 'success'){
			echo 'успешно обновлено';
		}elseif($status == 'error'){
			echo 'ошибка сохранения';
		}
		?>
	</div>
</section>

<?php
	$form = ActiveForm::begin([
	'id' => 'category-update-form'
]) ?>

<section class="white-bg">
	<div class="container wide">
		<div class="row tile thin">
			<div class="col-md-8">
				<div class="btn-group">
					<?php foreach ($languages as $key => $lang): ?>
						<?php $class = $model->lang_id == $lang->id ? 'btn-primary' : 'btn-default'; ?>

						<?= Html::a($lang->name, ['update', 'id' => $model->id, 'lang_id' => $lang->id], ['class' => 'btn '.$class.' ripple']) ?>
					<?php endforeach ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="btn-group text-md-right">
					<?= Html::submitButton('<i class="fa fa-save"></i> Сохранить', ['class' => 'btn btn-success ripple']) ?>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="mt2">
	<div class="container wide">

		<div class="row">
			<div class="col-md-9">
				<?= $form->field($model, 'alias')->textinput(['class' => 'full default']) ?>
			</div>
			<div class="col-md-3">

				<?= $form->field($model, 'published', [
					'options' => ['class' => 'form-group no-label'],
					'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
				])->checkbox([],false) ?>

			</div>
		</div>

		<section class="images mt1">
			<upload-images
				template="/assets/app/templates/upload-images-mini.html"
				url="<?= Url::toRoute(['upload-image', 'id' => $model->id]) ?>"
				delete="<?= Url::toRoute(['delete-image', 'id' => $model->id]) ?>"
				name="image"
				input='<?= $images ?>'
				output="form.files"
				multiple="true"
				title="Добавить фото"
			>
			</upload-images>
		</section>
		
	</div>
</section>

<section class="pt1 pb1">
	<div class="container wide">

		<?= $form->field($model->content, 'lang_id')->hiddenInput()->label(false); ?>
		<div class="row">
			<div class="col-md-9">
				<?= $form->field($model->content, 'title')->textInput(['class' => 'full default']) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($model->content, 'published', [
					'options' => ['class' => 'form-group no-label'],
					'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
				])->checkbox([],false) ?>
			</div>
		</div>

		<?= $form->field($model->content, 'description')->textArea(['class' => 'full default', 'rows' => '3']) ?>
		<?= $form->field($model->content, 'content')->widget(CKEditor::className(), [
			'options' => ['rows' => 6],
			'preset' => 'standart'
		]) ?>


	</div>
</section>

<?php ActiveForm::end() ?>
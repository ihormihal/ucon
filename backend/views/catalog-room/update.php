<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;

$this->params['breadcrumbs'][] = ['label' => $accommodation->getTitle($lang_id), 'url' => ['catalog-accommodation/update', 'id' => $accommodation->id]];
$this->params['breadcrumbs'][] = 'Update Room';
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
	'id' => 'room-update-form',
	'options' => [
		'class' => ''
	]
]) ?>


<section class="pt1 pb1">
	<div class="container wide">

		<div class="row">
			<div class="col-md-9">
				<?= $form->field($model, 'alias')->textinput(['class' => 'full default']) ?>
			</div>
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

		<ul class="tab-control nav nav-inline" data-target="#tabs-content">
			<li class="active"><a class="ripple" href="javascript:void(0)">Images</a></li>
			<li><a class="ripple" href="javascript:void(0)">Attributes</a></li>
		</ul>

		<div class="clear"></div>
		<div class="tab-content" id="tabs-content">
			<div class="tab fade active in">
				<div class="mt1"></div>
				<section class="images">
					<upload-images
						template="/design/app/templates/upload-images-mini.html"
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
			<div class="tab fade">
				<div class="mt1"></div>
				<?php 
					echo $this->render('_attributes', ['form' => $form, 'attributes' => $model->attrs]);
				?>
				<h3>Ценовые варианты</h3>
				<?php foreach ($prices as $price): ?>
				<div class="border white-bg box mt1">
					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<label>Selected attributes</label>
								<im-autocomplete-multiple
									value='[{"value": "6"}]'
									output="attributes"
									url="<?= Url::toRoute(['attributes', 'type' => 'bool']) ?>"
									min-length="0"
									custom="false"
									placeholder="Select attributes"
									class="default"
								></im-autocomplete-multiple>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label>Price</label>
								<input type="text" class="default full" value="<?= $price->price ?>">
							</div>
						</div>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</section>

<section>
	<div class="container wide">
		<div class="form-group btn-group mt2">
			<?php foreach ($languages as $key => $lang): ?>
				<?php $class = $lang_id == $lang->id ? 'btn-primary' : 'btn-default'; ?>

				<?= Html::a($lang->name, ['update', 'id' => $model->id, 'lang_id' => $lang->id], ['class' => 'btn btn-mt '.$class.' ripple']) ?>
			<?php endforeach ?>
		</div>

		<?= $form->field($content, 'lang_id')->hiddenInput(['value'=> $content->lang_id])->label(false); ?>
		<div class="row">
			<div class="col-md-9">
				<?= $form->field($content, 'title')->textInput(['class' => 'full default']) ?>
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
	</div>
</section>

<section>
	<div class="container wide">
		<div class="form-group btn-group border-top border-blue pt1">
			<?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'btn btn-mt btn-success']) ?>

			<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
				'class' => 'btn btn-mt btn-danger',
				'data' => [
					'confirm' => 'Are you sure you want to delete this item?',
					'method' => 'post',
				],
			]) ?>
		</div>
	</div>
</section>

<?php ActiveForm::end() ?>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use dosamigos\ckeditor\CKEditor;

$this->params['breadcrumbs'][] = ['label' => $accommodation->getTitle($lang_id), 'url' => ['catalog-accommodation/update', 'id' => $accommodation->id]];
$this->params['breadcrumbs'][] = 'Редактировать номер';
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
	'options' => ['class' => 'active-form']
]) ?>

<section class="white-bg">
	<div class="container wide">
		<div class="row tile thin">
			<div class="col-md-8">
				<div class="btn-group">
					<?php foreach ($languages as $key => $lang): ?>
						<?php $class = $lang_id == $lang->id ? 'btn-primary' : 'btn-default'; ?>

						<?= Html::a($lang->name, ['update', 'id' => $model->id, 'lang_id' => $lang->id], ['class' => 'btn '.$class.' ripple']) ?>
					<?php endforeach ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="btn-group text-md-right">
					<?= Html::submitButton('<i class="fa fa-save"></i> Сохранить', ['class' => 'btn btn-success']) ?>

					<?= Html::a('<i class="fa fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => 'Are you sure you want to delete this item?',
							'method' => 'post',
						],
					]) ?>
				</div>
			</div>
		</div>
	</div>
</section>


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
							<button class="btn btn-flat btn-success" disabled>Опубликовано</button>
						<?php else: ?>
							<button class="btn btn-flat btn-warning" disabled>Не опубликовано</button>
						<?php endif ?>
					</div>
				<?php endif ?>

			</div>
		</div>

		<ul class="tab-control nav nav-inline" data-target="#tabs-content">
			<li class="active"><a href="javascript:void(0)">Контент</a></li>
			<li><a href="javascript:void(0)">Фото</a></li>
			<li><a href="javascript:void(0)">Атрибуты</a></li>
			<li><a href="javascript:void(0)">Цены</a></li>
			<li><a href="javascript:void(0)">Сезонные скидки</a></li>
		</ul>

		<div class="clear"></div>
		<div class="tab-content" id="tabs-content">
			<div class="tab fade active in">
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
			<div class="tab fade">
				<div class="mt1"></div>
				<section class="images">
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
			<div class="tab fade">
				<div class="mt1"></div>
				<?= $this->render('_attributes', ['form' => $form, 'attributes' => $model->attrs]) ?>
			</div>
			<div class="tab fade" 
				catalog-variants="<?= Url::toRoute(['get-variants', 'id' => $model->id, 'lang_id' => $lang_id]) ?>"
				update-url="<?= Url::toRoute(['update-variants', 'id' => $model->id, 'lang_id' => $lang_id]) ?>"
				>
				<div class="mt1"></div>
				<?= $this->render('_variants', []) ?>
			</div>
			<div class="tab fade" 
				catalog-variants="<?= Url::toRoute(['get-discounts', 'id' => $model->id]) ?>"
				update-url="<?= Url::toRoute(['update-discounts', 'id' => $model->id]) ?>"
				>
				<div class="mt1"></div>
				<?php if ($model->discount): ?>
				<div class="mb1 box orange-bg">
					<span class="thin">Скидка на сегодня:</span> <?= $model->discount->discount ?>%
				</div>
				<?php endif ?>
				<?= $this->render('_discounts', ['model' => $model]) ?>
			</div>
		</div>
	</div>
</section>

<?php ActiveForm::end() ?>
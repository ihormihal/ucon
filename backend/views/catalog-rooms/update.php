<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */

//$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $content->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => $accomodation->getTitle(), 'url' => ['catalog-accommodation/update', 'id' => $accomodation->id]];
$this->params['breadcrumbs'][] = 'Update Room';
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
            <span class="thin">Статус:</span> редактирование номера
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

					<?= $form->field($model, 'active', [
						'options' => ['class' => 'form-group no-label'],
						'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
					])->checkbox([],false) ?>

				</div>
			</div>

			<ul class="tab-control nav nav-inline" data-target="#tabs-content">
				<li class="active"><a class="ripple" href="javascript:void(0)">Content</a></li>
				<li><a class="ripple" href="javascript:void(0)">Images</a></li>
				<li><a class="ripple" href="javascript:void(0)">Attributes</a></li>
			</ul>

			<div class="clear"></div>
			<div class="tab-content" id="tabs-content">
				<div class="tab fade active in">

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
							<?= $form->field($content, 'active', [
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
				</div>
			</div>
		</div>
	</section>

	<section>
    	<div class="container wide">
    		<div class="form-group btn-group border-top border-blue pt1">
				<?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'btn btn-mt btn-primary']) ?>

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

 </div>
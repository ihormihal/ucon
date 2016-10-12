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
$this->params['breadcrumbs'][] = 'Update';
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
            <span class="thin">Статус:</span> редактирование категории
        </div>
    </section>

    <?php
		$form = ActiveForm::begin([
		'id' => 'category-update-form',
		'options' => [
			'class' => ''
		]
	]) ?>


    <section class="mt2">
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

			<section class="images mt1 mb2">
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
    </section>

    <section class="pt1 pb1">
    	<div class="container wide">

    		<div class="form-group btn-group">
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


			<div class="form-group">
				<?= Html::submitButton('<i class="fa fa-check"></i> Save', ['class' => 'btn btn-mt btn-success']) ?>
			</div>

    	</div>
    </section>

    <?php ActiveForm::end() ?>

 </div>
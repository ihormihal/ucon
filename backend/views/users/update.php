<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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

<section class="pb1">
	<div class="container wide">

		<h1 class="mt1 mb1"><?= $this->title ?></h1>

		<?php $form = ActiveForm::begin([
			'options' => ['autocomplete' => 'off']
		]); ?>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'username')->textInput(['class' => 'default full']) ?>
				<?= $form->field($model, 'email')->textInput(['class' => 'default full']) ?>
				<?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off', 'class' => 'default full']) ?>
				<?= $form->field($model, 'role')->dropDownList([
					'' => 'Нет роли',
					'vendor' => 'Вендор',
					'content' => 'Контент-менеджер',
					'admin'=>'Администратор'
				], ['class' => 'default full']); ?>
			</div>
		</div>

		<div class="form-group btn-group mt1">
			<?= Html::submitButton(
					'<i class="fa fa-save"></i> Сохранить',
					['class' => 'btn btn-success ripple']
			) ?>

			<?php if ($model->id !== 1 || Yii::$app->user->id !== $model->id): ?>
			<?= Html::a(
				'<i class="fa fa-trash"></i> Удалить пользователя',
				['delete', 'id' => $model->id], 
				[
					'class' => 'btn btn-danger ripple',
					'data' => [
						'confirm' => 'Are you sure you want to delete this user?',
						'method' => 'post',
					],
				]
			)?>
			<?php endif ?>
		</div>
		<?php ActiveForm::end(); ?>

	</div>
</section>

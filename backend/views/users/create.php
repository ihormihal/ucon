<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="pt1 pb1 orange-bg">
	<div class="container wide">
		<span class="thin">Статус:</span> добавление пользователя
	</div>
</section>

<section class="pb1">
	<div class="container wide">

		<h1 class="mt1 mb1">Новый пользователь</h1>

		<?php $form = ActiveForm::begin([
			'options' => ['autocomplete' => 'off']
		]); ?>

		<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'username')->textInput(['class' => 'default full']) ?>
				<?= $form->field($model, 'email')->textInput(['class' => 'default full']) ?>
				<?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'off', 'class' => 'default full']) ?>
				<?= $form->field($model, 'role')->dropDownList([
					'vendor' => 'Вендор',
					'content' => 'Контент-менеджер',
					'admin'=>'Администратор'
				], ['class' => 'default full']); ?>
			</div>
		</div>

		<div class="form-group btn-group mt1">
			<?= Html::submitButton(
					'<i class="fa fa-save"></i> Создать пользователя',
					['class' => 'btn btn-success ripple']
				) 
			?>
		</div>
		<?php ActiveForm::end(); ?>

	</div>
</section>
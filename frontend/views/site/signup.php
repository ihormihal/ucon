<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="box mt2 white-bg">
				<h1><?= Html::encode($this->title) ?></h1>

				<p>Please fill out the following fields to signup:</p>

				<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

					<?= $form->field($model, 'username')->textInput(['class' => 'full mt', 'autofocus' => true]) ?>

					<?= $form->field($model, 'email')->textInput(['class' => 'full mt']) ?>

					<?= $form->field($model, 'password')->passwordInput(['class' => 'full mt']) ?>

					<div class="form-group">
						<?= Html::submitButton('Signup', ['class' => 'btn btn-mt btn-primary', 'name' => 'signup-button']) ?>
					</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>


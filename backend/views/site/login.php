<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box mt2 white-bg">
                <h1><?= Html::encode($this->title) ?></h1>

                <p>Please fill out the following fields to login:</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['class' => 'full mt', 'autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput(['class' => 'full mt']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{labelTitle}</label>{error}</div>'
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-mt btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

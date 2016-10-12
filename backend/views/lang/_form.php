<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Lang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lang-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'lng')->textInput(['maxlength' => true, 'class' => 'default full']) ?>
            <?= $form->field($model, 'locale')->textInput(['maxlength' => true, 'class' => 'default full']) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'default full']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'active', [
                'options' => ['class' => 'form-group no-label'],
                'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
            ])->checkbox([],false) ?>

            <?= $form->field($model, 'default', [
                'options' => ['class' => 'form-group no-label'],
                'template' => '<div class="checkbox"><label>{input}<span class="check"></span>{label}</label>{error}</div>'
            ])->checkbox([],false) ?>
        </div>
    </div>

    <div class="form-group btn-group">
        
        <?= Html::submitButton(
                $model->isNewRecord ? '<i class="fa fa-check"></i> Create' : '<i class="fa fa-check"></i> Update', 
                ['class' => $model->isNewRecord ? 'btn btn-mt btn-success' : 'btn btn-mt btn-success']
            ) 
        ?>

        <?php 
            if (!$model->isNewRecord){
                echo Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-mt btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

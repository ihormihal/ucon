<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php
	$form = ActiveForm::begin([
	'id' => 'attribute-update-form',
	'options' => [
		'class' => '',
	]
]) ?>

<?= $form->field($model, 'model_name')->hiddenInput(['value'=> $model_name])->label(false); ?>


<?php
	$nameInputConf = ['class' => 'full default'];
	$aliasInputConf = ['class' => 'full default'];
	if($model->isNewRecord){
		$nameInputConf['ng-model'] = 'toalias';
		$aliasInputConf['ng-model'] = 'alias';
	}
?>
<div class="row">
	<div class="col-md-8">
		<?= $form->field($model, 'name')->textInput($nameInputConf) ?>
	</div>
	<div class="col-md-4">
		<?= $form->field($model, 'alias')->textInput($aliasInputConf) ?>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
		<?= $form->field($model, 'type')->dropDownList(
			[
				'db' => 'База данных',
				'bool' => 'Да/Нет',
				'number' => 'Число',
				'text' => 'Текст',
				'values' => 'Набор значений',
				'fields' => 'Набор полей',
			],
			['ng-initial' => '', 'ng-model' => 'attributeType', 'class' => 'full default']
		) ?>
	</div>
	<div class="col-md-4">
		<div class="hidden">
			{{attributeValues = []}}
		</div>
		<div ng-show="attributeType == 'db'">
			<?= $form->field($model, 'values_model_name')->dropDownList(
				[
					'' => 'Не выбрано',
					'DataCity' => 'Города',
					'DataCountry' => 'Страны',
				],
				['class' => 'full default']
			) ?>
		</div>
	</div>
</div>

<div json-collection json="<?= $model->values ?>" ng-show="attributeType == 'values' || attributeType == 'fields'">

	<?= $form->field($model, 'values_config')->hiddenInput(['value' => '{{json}}'])->label(false); ?>

	<div class="row" ng-repeat="value in collection track by $index">
		<div class="col-md-8" >
			<div class="form-group">
				<input class="default full" type="text" ng-model="collection[$index]" ng-model-options="{updateOn: 'blur'}">
			</div>
		</div>
		<div class="col-md-4">
			<a ng-click="removeItem($index)" class="btn btn-danger ripple"><i class="fa fa-trash"></i> Удалить</a>
		</div>
	</div>
	
	<div class="form-group">
		<a ng-click="addItem()" class="btn btn-primary ripple"><i class="fa fa-plus"></i> Добавить</a>
	</div>
</div>

<div class="form-group btn-group mt1">
        
    <?= Html::submitButton(
            $model->isNewRecord ? '<i class="fa fa-save"></i> Создать' : '<i class="fa fa-save"></i> Сохранить',
            ['class' => 'btn btn-success ripple']
        ) 
    ?>

    <?php 
        if (!$model->isNewRecord){
            echo Html::a('<i class="fa fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger ripple',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
    ?>
</div>

<?php ActiveForm::end() ?>
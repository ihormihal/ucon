<?php
use yii\helpers\Url;
?>
<div class="white-bg box mb1" ng-repeat="variant in variants" ng-class="{'removed': variant.removed}">
	<h4>Вариант #{{$index+1}} <span class="red" ng-show="variant.removed">(будет удалено)</span></h4>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Описание</label>
				<textarea rows="3" class="full default" ng-model="variant.description"></textarea>
			</div>
		</div>
		<div class="col-md-8">
			<div class="form-group">
				<label>Выбранные атрибуты</label>
				<im-autocomplete-multiple
					ng-model="variant.attributes"
					output="attributes"
					url="<?= Url::toRoute(['catalog-attribute/attributes', 'model_name' => 'CatalogRoom', 'type' => 'bool']) ?>"
					min-length="0"
					custom="false"
					placeholder="Select attributes"
					class="default"
				></im-autocomplete-multiple>
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Цена</label>
				<input type="text" class="default full" ng-model="variant.price">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group no-label">
				<a class="btn btn-danger ripple full" ng-click="remove($index)" ng-hide="variant.removed"><i class="fa fa-trash"></i> Удалить</a>
				<a class="btn btn-primary ripple full" ng-click="restore($index)" ng-show="variant.removed">Восстановить</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<a class="btn btn-primary ripple" ng-click="add()"><i class="fa fa-plus"></i> Добавить вариант</a>
	</div>
	<div class="col-md-6 text-right">
		<a class="btn btn-success ripple" ng-click="save()"><i class="fa fa-save"></i> Сохранить</a>
	</div>
</div>
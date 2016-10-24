<?php
use yii\helpers\Url;
?>
<div class="white-bg box mb1" ng-repeat="variant in variants" ng-class="{'removed': variant.removed}">
	<h4>Период #{{$index+1}} <span class="red" ng-show="variant.removed">(будет удалено)</span></h4>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>От</label>
				<input texttime="" type="date" class="default full" ng-model="variant.period_from">
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>До</label>
				<input texttime="" type="date" class="default full" ng-model="variant.period_to">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
				<label>Скидка, %</label>
				<input type="text" class="default full" ng-model="variant.discount">
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group no-label">
				<a class="btn btn-danger ripple" ng-click="remove($index)" ng-hide="variant.removed"><i class="fa fa-trash"></i> Удалить</a>
				<a class="btn btn-primary ripple" ng-click="restore($index)" ng-show="variant.removed">Восстановить</a>
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
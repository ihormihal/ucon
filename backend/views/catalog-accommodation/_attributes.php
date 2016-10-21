<?php
use yii\helpers\Json;
?>


<!-- Attributes -->
<div class="row">
<?php foreach ($attributes as $alias => $attribute): ?>

<?php
	$label = $attribute->config->name;
	$name = 'Attributes['.$alias.']';
?>


	<?php if ($attribute->config->type == 'db'): ?>
	<div class="col-md-4">
		<div class="form-group">
			<label><?= $label ?></label>
			<select name="<?= $name ?>" class="default full">
				<option value=""></option>
			</select>
		</div>
	</div>
	<?php elseif($attribute->config->type == 'bool'): ?>
	<div class="col-md-12">
		<div class="form-group">
			<div class="checkbox">
				<label>
					<input type="hidden" name="<?= $name ?>" value="{{<?= $alias ?>}}">
					<input 
						type="checkbox"
						ng-initial="<?= $attribute->value; ?>"
						ng-model="<?= $alias ?>"
						ng-true-value="'1'"
						ng-false-value="'0'"
					>
					<span class="check"></span>
					<?= $label ?>
				</label>
			</div>
		</div>
	</div>
	<?php elseif($attribute->config->type == 'text'): ?>
	<div class="col-md-12">
		<div class="form-group">
			<label><?= $label ?></label>
			<input name="<?= $name ?>" type="text" class="default full" value="<?= $attribute->value ?>">
		</div>
	</div>
	<?php elseif($attribute->config->type == 'number'): ?>
	<div class="col-md-12">
		<div class="form-group">
			<label><?= $label ?></label>
			<input name="<?= $name ?>" type="number" class="default full" value="<?= $attribute->value ?>">
		</div>
	</div>
	<?php elseif($attribute->config->type == 'values'): ?>
	<div class="col-md-12">
		<div class="form-group">
			<label><?= $label ?></label>
			<select class="default full" name="<?= $name ?>">
				<?php  $config_values = json_decode($attribute->config->values_config, true); ?>
				<?php foreach ($config_values as $value): ?>
					<option><?= $value ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</div>
	<?php elseif($attribute->config->type == 'fields'): ?>
	<div class="col-md-12">
		<div class="form-group">
			<?php 
				$config_fields = json_decode($attribute->config->values_config, true);
				$values = JSON::decode($attribute->value);
			?>

			<label><?= $label ?></label>
			<div class="row">
				<?php foreach ($config_fields as $field): ?>
					<div class="col-md-4">
						<div class="form-group">
							<label><?= $field ?></label>
							<input name="<?= $name.'['.$field.']' ?>" value="<?= $values[$field] ?>" class="default full" type="text">
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
	<?php endif ?>

<?php endforeach ?>
</div>
<!-- Attributes END -->
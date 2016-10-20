<?php 
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;


$menuItems = [];

$menuItems[] = [
	'label' => '<i class="icon fa fa-building"></i> <span class="title">Гостиницы</span>', 
	'url' => ['catalog-accommodation/index']
];

if(Yii::$app->user->can('adminAccess'))
{
	$menuItems[] = [
		'label' => '<i class="icon fa fa-star"></i> <span class="title">Атрибуты</span>', 
		'url' => 'javascript:void(0)',
		'items' => [
			['label' => '<i class="icon fa fa-building"></i> <span class="title">Гостиницы</span>', 'url' => ['catalog-attributes/index', 'model_name' => 'CatalogAccommodation']],
			['label' => '<i class="icon fa fa-bed"></i> <span class="title">Номера</span>', 'url' => ['catalog-attributes/index', 'model_name' => 'CatalogRooms']],
		]
	];
	$menuItems[] = [
		'label' => '<i class="icon fa fa-language"></i> <span class="title">Языки</span>', 
		'url' => ['lang/index']
	];
	$menuItems[] = [
		'label' => '<i class="icon fa fa-users"></i> <span class="title">Пользователи</span>', 
		'url' => ['users/index']
	];
}

?>
<div class="aside-menu">
	<nav>
		<?= Nav::widget([
			'encodeLabels' => false,
			'options' => ['class' => ''],
			'items' => $menuItems,
		]); ?>
	</nav>
</div>
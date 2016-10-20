<?php
return [
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
			//'defaultRoles' => ['admin'], //здесь прописываем роли
		],
	],
	'aliases' => [
		'@root' => realpath(dirname(__FILE__).'/../../'),
	]
];

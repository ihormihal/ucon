<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);


$menuItems = [
	['label' => Yii::t('translate','Home'), 'url' => ['/']],
	['label' => Yii::t('translate','Hotels'), 'url' => ['catalog-accommodation/index']],
	['label' => Yii::t('translate','About'), 'url' => ['/about/']],
	['label' => Yii::t('translate','Contact'), 'url' => ['/contact/']],
];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
		<?= Html::csrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" type="text/javascript"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js" type="text/javascript"></script>
		<![endif]-->
	</head>
	<body ng-app="app">
		<?php $this->beginBody() ?>
		<header class="shadow-1">
			<?= $this->render('main/_navbar', ['menuItems' => $menuItems]); ?>
		</header>
		<main ng-controller="mainController">

			<?= Alert::widget() ?>
        	<?= $content ?>

		</main>

		<footer>
			<?= $this->render('main/_footer'); ?>
		</footer>

	<?php $this->endBody() ?>
	</body>

</html>
<?php $this->endPage() ?>
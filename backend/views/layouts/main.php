<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

use common\widgets\Alert;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="admin">
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
		<header>
			<div class="navbar fixed top left full white-bg shadow-2">
				<div class="container wide">
					<div class="row">
						<div class="col-md-ten-2 col-xs-8">
							<div class="">
								<div class="logo">
									<img src="/assets/images/logo.png" alt="">
								</div>
							</div>
						</div>
						<div class="col-md-ten-4 col-xs-4">
							<div class="notifications">
								<a href="javascript:void(0)" class="note" data-count="3">
									<i class="fa fa-calendar"></i>
								</a>
								<a href="javascript:void(0)" class="note" data-count="9">
									<i class="fa fa-envelope"></i>
								</a>
							</div>
						</div>
						<div class="col-md-ten-4 col-xs-12">
							<div class="right">
								<nav class="show-lg-over">
									<ul class="nav nav-inline">
										<?php if (Yii::$app->user->isGuest): ?>
											<li><a class="btn btn-mt btn-primary" href="/site/login"><i class="fa fa-sign-in"></i> Login</a></li>
										<?php else: ?>
											<li>
											<?= Html::beginForm(['/site/logout'], 'post'); ?>
											<?= Html::submitButton('<i class="fa fa-sign-out"></i> Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-mt btn-primary']); ?>
											<?= Html::endForm(); ?>
											</li>
										<?php endif ?>
									</ul>
								</nav>
								<div class="menu-open-bar text-right show-md-under"><i class="mti">menu</i></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="navbar-fixed-space"></div>
			<div class="menu-slide slide-left white-bg">
				<nav>
					<ul class="nav nav-col border-bottom">
						<?php if (Yii::$app->user->isGuest): ?>
							<li><a class="btn btn-mt btn-primary" href="/site/login"><i class="fa fa-sign-in"></i> Login</a></li>
						<?php else: ?>
							<li>
							<?= Html::beginForm(['/site/logout'], 'post'); ?>
							<?= Html::submitButton('<i class="fa fa-sign-out"></i> Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-mt btn-primary']); ?>
							<?= Html::endForm(); ?>
							</li>
						<?php endif ?>
					</ul>
				</nav>
			</div>
			<div class="page-overlay"></div>
		</header>
		<main class="collumns" ng-controller="mainController">

			<div class="col-aside dark-bg">
				<?= $this->render('/site/nav/aside'); ?>
			</div>
			<div class="col-main">

				<?= Alert::widget() ?>
				
				<?php if (isset($this->params['breadcrumbs'])): ?>
					<section class="pt1 pb1 white-bg">
						<div class="container wide">	
							<?= Breadcrumbs::widget([
								'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
							]) ?>
						</div>
					</section>
				<?php endif ?>
				
				<?= $content ?>

			</div>

		</main>

	<?php $this->endBody() ?>
	</body>

</html>
<?php $this->endPage() ?>
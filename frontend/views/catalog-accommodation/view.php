<?php
use frontend\models\Lang;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\widgets\SearchHotelsAside;

$this->title = $model->content['title'];

$this->params['breadcrumbs'][] = ['label' => 'Hotels', 'url' => ['catalog-accommodation/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="page-header pt1 pb1">
    <div class="container wide">
        <div class="row">
            <div class="col-md-6">
                <h1 class="white"><?= Html::encode($this->title) ?></h1>
                <div class="stars">
					<?php for ($i = 0; $i < $model->stars; $i++): ?>
						<i class="fa fa-star"></i>
					<?php endfor ?>
				</div>
            </div>
            <div class="col-md-6">
                <div class="white lh1 right-md">
					<?= Breadcrumbs::widget([
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					]) ?>
				</div>
            </div>
        </div>
    </div>
</section>

<div class="mb2"></div>

<div class="container wide page-hotel">

	<div class="row">

		<div class="col-md-3">
			<div class="aside">
				

				<a href="#rooms" class="btn btn-mt btn-warning full mb1"><?= Yii::t('translate','book') ?></a>
				
				<?php if(array_key_exists('location', $model->attrs)): ?>
				<div class="shadow-2 lighten-bg mb1">
					<div
						class="map single"
						data-location="<?= $model->attrs['location'] ?>"
						data-zoom="14"
						data-marker="/assets/img/pin.svg"
						style="height: 300px;"
					>
					</div>
				</div>
				<?php endif; ?>

				<div class="box shadow-2 white-bg mb1">
					<div class="row">
						<div class="col-xs-8">
							<div class="rating">
								<i class="fa fa-star active"></i>
								<i class="fa fa-star active"></i>
								<i class="fa fa-star active"></i>
								<i class="fa fa-star active"></i>
								<i class="fa fa-star"></i>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="rating text-right">4.0</div>
						</div>
					</div>
					<div class="mt1 mb1">
						<div class="inline-rate">
							<div class="name"><?= Yii::t('translate','Purity') ?></div>
							<div class="rate"><span style="width: 80%;"></span></div>
							<div class="value">4.0</div>
						</div>
						<div class="inline-rate">
							<div class="name"><?= Yii::t('translate','Comfort') ?></div>
							<div class="rate"><span style="width: 90%;"></span></div>
							<div class="value">4.5</div>
						</div>
						<div class="inline-rate">
							<div class="name"><?= Yii::t('translate','Location') ?></div>
							<div class="rate"><span style="width: 70%;"></span></div>
							<div class="value">3.5</div>
						</div>
					</div>
				</div>

				<?= SearchHotelsAside::widget();?>

			</div>
		</div>

		<div class="col-md-9">
			
			<section class="gallery">
				<div class="fotorama" data-nav="thumbs" data-allowfullscreen="true">
					<?php foreach ($model->getImages() as $image): ?>
						<?= Html::img($image->getUrl()) ?>
					<?php endforeach ?>
				</div>
			</section>

			<section class="content mt2">

				<h3 class="pb1"><?= Yii::t('translate','Hotel_description') ?></h3>
				<?= $model->content['content'] ?>

				<div id="rooms" class="rooms pt1">
					<?= $this->render('_rooms', [
						'rooms' => $rooms,
					]) ?>
				</div>

				<ul class="tab-control nav nav-inline" data-target="#tabs-horizontal">
					<li class="active"><a class="ripple" href="javascript:void(0)"><?= Yii::t('translate','Reviews') ?></a></li>
					<li><a class="ripple" href="javascript:void(0)"><?= Yii::t('translate','Map') ?></a></li>
				</ul>

				<div class="clear"></div>
				<div class="tab-content" id="tabs-horizontal">
					<div class="tab fade active in">
						<h3 class="pb1"><?= Yii::t('translate','Hotel_description') ?></h3>
						<?= $model->content['content'] ?>
					</div>
					<div class="tab fade">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
							Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					</div>
				</div>
			</section>

		</div>
		
	</div>
</div>
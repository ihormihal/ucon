<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php foreach ($rooms as $room): ?>
	<?php 
		if($room->content === null) continue;
	?>
	<div class="white-bg room box shadow-2 mb1">

		<div class="row">
			<div class="col-md-3">
				<div class="image hover-scale">
					<?= Html::img($room->getImage()->getUrl('500x300')) ?>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row">
					<div class="col-xs-9">
						<h3 class="title"><?= $room->content['title'] ?></h3>
					</div>
					<div class="col-xs-3">
						<div class="persons tooltip">
							<span class="tip">Двуместный номер</span>
							<i class="fa fa-user"></i>
							<i class="fa fa-user"></i>
						</div>
					</div>
				</div>
				<div class="services icons mt1">
					<div class="icon-square tooltip"><span class="tip">Завтрак в номер</span><i class="fa fa-cutlery"></i></div>
					<div class="icon-square tooltip"><span class="tip">Дополнительная кровать</span><i class="fa fa-bed"></i></div>
					<div class="icon-square tooltip"><span class="tip">Бесплатная парковка</span><i class="fa fa-car"></i></div>
					<div class="icon-square tooltip"><span class="tip">Бесплатный Wi-Fi</span><i class="fa fa-wifi"></i></div>
				</div>
			</div>
		</div>

		<div class="description mt1">
			<?= $room->content['content'] ?>
		</div>

		<div class="variants">
			<div class="variant">
				<div class="row">
					<div class="col-md-6">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.
					</div>
					<div class="col-md-3">
						<div class="price text-right">
							<span class="currency">UAH</span><span class="value">800</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="text-right">
							<a href="javascript:void(0)" class="btn btn-mt btn-primary"><?= Yii::t('translate','book') ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="variant">
				<div class="row">
					<div class="col-md-6">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.
					</div>
					<div class="col-md-3">
						<div class="price text-right">
							<span class="currency">UAH</span><span class="value">1000</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="text-right">
							<a href="javascript:void(0)" class="btn btn-mt btn-primary"><?= Yii::t('translate','book') ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="variant">
				<div class="row">
					<div class="col-md-6">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.
					</div>
					<div class="col-md-3">
						<div class="price text-right">
							<span class="currency">UAH</span><span class="value">1200</span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="text-right">
							<a href="javascript:void(0)" class="btn btn-mt btn-primary"><?= Yii::t('translate','book') ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>




		<!-- <div class="row condensed">
			<div class="col-md-4 header">
				<div class="image hover-scale">
					<?= Html::img($room->getImage()->getUrl('500x300')) ?>
				</div>
			</div>
			<div class="col-md-8 content">
				<div class="box">
					<div class="row thin">
						<div class="col-xs-9">
							<h3 class="title"><?= $room->content['title'] ?></h3>
						</div>
						<div class="col-xs-3">
							<div class="persons tooltip">
								<span class="tip">Двуместный номер</span>
								<i class="fa fa-user"></i>
								<i class="fa fa-user"></i>
							</div>
						</div>
					</div>
					<p class="grey">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
					<div class="services icons">
						<div class="icon-square tooltip"><span class="tip">Завтрак в номер</span><i class="fa fa-cutlery"></i></div>
						<div class="icon-square tooltip"><span class="tip">Дополнительная кровать</span><i class="fa fa-bed"></i></div>
						<div class="icon-square tooltip"><span class="tip">Бесплатная парковка</span><i class="fa fa-car"></i></div>
						<div class="icon-square tooltip"><span class="tip">Бесплатный Wi-Fi</span><i class="fa fa-wifi"></i></div>
					</div>
				</div>
				<div class="actions">
					<div class="row thin">
						<div class="col-xs-6">
							<a href="javascript:void(0)" class="btn btn-flat btn-success"><?= Yii::t('translate','book') ?></a>
						</div>
						<div class="col-xs-6">
							<div class="price text-right">
								<span class="currency">UAH</span><span class="value">800</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> -->
	</div>
<?php endforeach ?>
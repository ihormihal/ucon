<?php
use yii\helpers\Html;
?>
<div class="pt3 pb3 text-center">
	<h2><?= Yii::t('translate','Rooms') ?></h2>
</div>
<div class="row tile">
	<?php foreach ($rooms as $room): ?>
		<?php 
			if($room->content === null) continue;
		?>
		<div class="col-lg-3 col-md-6">
			<div class="card-box room shadow-2 shadow-3-hover">
				<div class="header image hover-scale">
					<?= Html::img($room->getImage()->getUrl('500x300')) ?>
				</div>
				<div class="content">
					<div class="box">
						<div class="row thin">
							<div class="col-xs-7">
								<h3 class="title"><a href="#"><?= $room->content['title'] ?></a></h3>
								<p class="description">Киев <?= $room->alias ?></p>
							</div>
							<div class="col-xs-5">
								<div class="price text-right">
									<span class="currency">UAH</span><span class="value">800</span>
									<p class="description"><?= Yii::t('translate','day') ?></p>
								</div>
							</div>
						</div>

						<div class="rating">
							<div class="row thin">
								<div class="col-xs-6">
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star"></i>
								</div>
								<div class="col-xs-6 text-right reviews">
									5 <?= Yii::t('translate','reviews') ?>
								</div>
							</div>
						</div>
						
						<p class="grey"><?= $room->content['description'] ?></p>
					</div>
					<div class="actions">
						<a href="javascript:void(0)" class="btn btn-flat btn-success"><?= Yii::t('translate','book') ?></a>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
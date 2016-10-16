<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php foreach ($hotels as $hotel): ?>
	<?php 
		if($hotel->content === null) continue;
	?>

	<div class="card-row white-bg hotel shadow-2 shadow-3-hover">
		<div class="row condensed">
			<div class="col-md-4 header">
				<div class="image hover-scale">
					<?= Html::img($hotel->getImage()->getUrl('500x300')) ?>
				</div>
			</div>
			<div class="col-md-8 content">
				<div class="box">
					<div class="row thin">
						<div class="col-xs-9">
							<h3 class="title"><?= $hotel->content['title'] ?></h3>
						</div>
						<div class="col-xs-3">
							
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
							<?= Html::a(Yii::t('translate','choose'), ['view', 'id' => $hotel->id], ['class' => 'btn btn-flat btn-success']) ?>
						</div>
						<div class="col-xs-6">
							<div class="price text-right pr1">
								<?= Yii::t('translate','from') ?> <span class="currency">UAH</span><span class="value">800</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php endforeach ?>
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
						<!-- <div class="persons tooltip">
							<span class="tip">Двуместный номер</span>
							<i class="fa fa-user"></i>
							<i class="fa fa-user"></i>
						</div> -->
						<?php if ($room->discount['discount'] > 0): ?>
							<div class="discount tooltip tooltip-right">
								<span class="tip">Текущая скидка</span>
								<div class="value">- <?= $room->discount['discount'] ?> <span>%</span></div>
							</div>
						<?php endif ?>
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
			<?php foreach ($room->variants as $variant): ?>
				<div class="variant">
					<div class="row">
						<div class="col-md-6">
							<b><?= $variant->content['description'] ?></b>
							<div class="services list mt1">
								<?php foreach ($variant->attrs as $attr): ?>
									<div class="list-item"><i class="fa fa-circle"></i> <?= $attr->name ?></div>
								<?php endforeach ?>
							</div>
						</div>
						<div class="col-md-3">
							<div class="price text-right">
								<span class="currency">UAH</span><span class="value"><?= $variant->price ?></span>
							</div>
						</div>
						<div class="col-md-3">
							<div class="text-right">
								<?= Html::a(Yii::t('translate','book'), ['booking/index', 'id' => $room->id, 'variant' => $variant->id], ['class' => 'btn btn-mt btn-primary']) ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div>

	</div>
<?php endforeach ?>
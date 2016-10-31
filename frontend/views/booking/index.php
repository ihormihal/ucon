<?php
use frontend\models\Lang;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = 'Новое бронирование';

$this->params['breadcrumbs'][] = [
	'label' => $room->accommodation->content['title'], 
	'url' => ['catalog-accommodation/view', 'id' => $room->accommodation->id]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="page-header pt1 pb1">
    <div class="container wide">
        <div class="row">
            <div class="col-md-6">
                <h1 class="white"><?= Html::encode($this->title) ?></h1>
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

<div class="container wide page-booking">

	<section class="steps mb2">
		<div class="step step-1">1. Выберите номер</div>
		<div class="step step-2 active">2. Введите свои данные</div>
		<div class="step step-3">3. Подтвердите свое бронирование</div>
	</section>

	<section class="box shadow-2 white-bg mb2">
		<div class="row">
			<div class="col-md-4">
				<div class="image hover-scale">
					<?= Html::img($room->accommodation->getImage()->getUrl('500x300')) ?>
				</div>
			</div>
			<div class="col-md-8">
				<h3 class="title"><?= $room->accommodation->content['title'] ?>
					<span class="stars">
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
						<i class="fa fa-star"></i>
					</span>
				</h3>
				<div class="details">
					<div><i class="fa fa-map-marker"></i> ул.Крещатик 1, Киев, Украина</div>
					<div><i class="fa fa-map-marker"></i> ул.Крещатик 1, Киев, Украина</div>
				</div>
				<div class="mt1"><?= $room->accommodation->content['content'] ?></div>
			</div>
		</div>
		<div class="border-top mt1 mb1"></div>
		<div class="row">
			<div class="col-md-3">
				<div class="image hover-scale">
					<?= Html::img($room->getImage()->getUrl('500x300')) ?>
				</div>
			</div>
			<div class="col-md-9">
				<h3 class="title"><?= $room->content['title'] ?></h3>
				<div class="services icons mt1">
					<div class="icon-square tooltip"><span class="tip">Завтрак в номер</span><i class="fa fa-cutlery"></i></div>
					<div class="icon-square tooltip"><span class="tip">Дополнительная кровать</span><i class="fa fa-bed"></i></div>
					<div class="icon-square tooltip"><span class="tip">Бесплатная парковка</span><i class="fa fa-car"></i></div>
					<div class="icon-square tooltip"><span class="tip">Бесплатный Wi-Fi</span><i class="fa fa-wifi"></i></div>
				</div>
				<div class="mt1"><?= $room->content['content'] ?></div>
			</div>
		</div>
	</section>

	<div class="row">

		<div class="col-md-3">
			<div class="aside">

				<div class="box shadow-2 white-bg mb2">
					<div class="form-group">
						<label>Приезжаю</label>
						<div class="input-icon">
							<im-datepicker inpopup="true">
								<input class="full mtr" type="text" value="23.10.2016">
							</im-datepicker>
							<i class="icon fa fa-calendar"></i>
						</div>
					</div>
					<div class="form-group">
						<label>Уезжаю</label>
						<div class="input-icon">
							<im-datepicker inpopup="true">
								<input class="full mtr" type="text" value="30.10.2016">
							</im-datepicker>
							<i class="icon fa fa-calendar"></i>
						</div>
					</div>
					<div class="form-group">
						<label>Всего ночей:</label>
						<span>6</span>
					</div>
					<div class="form-group">
						<label>Цена (итоговая стоимость)</label>
						<div class="price-total">UAH 5400</div>
					</div>
				</div>

			</div>
		</div>

		<div class="col-md-9">

			<section class="box shadow-2 white-bg mb2">

				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label>Обращение <sup>*</sup></label>
							<select class="full mtr">
								<option>Г-н.</option>
								<option>Г-жа.</option>
							</select>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label>Имя (латиницей) <sup>*</sup></label>
							<input type="text" class="full mtr">
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label>Фамилия (латиницей) <sup>*</sup></label>
							<input type="text" class="full mtr">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label>E-mail <sup>*</sup></label>
							<input type="text" class="full mtr">
						</div>
						<div class="form-group">
							<label>E-mail повторно <sup>*</sup></label>
							<input type="text" class="full mtr">
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group no-label">
							<span class="thin grey italic">На этот адрес будет отправлено подтверждение бронирования</span>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label>Создайте пароль (необязательно)</label>
							<input type="password" class="full mtr">
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group no-label">
							<span class="thin grey italic">Добавьте пароль для управления бронированием</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label>Ваши пожелания</label>
					<textarea rows="4" class="full mtr"></textarea>
				</div>

				<div class="text-right mt2">
					<button class="btn btn-mt btn-primary">Продолжить</button>
				</div>
				
			</section>

		</div>
		
	</div>
</div>